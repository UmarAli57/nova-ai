<?php 

namespace App\Controller;

use App\Support\Http;
use BuildQL\Database\Query\DB;

class ChatController extends Controller {
    protected string $rawBuffer = "";

    protected string $errorBuffer = "";

    protected string $fullBufferText = "";

    protected int $historyLimit = 6;

    const AVAILABLE_MODELS = [
        "default" => "nova-2.0",
        "pro" => "nova-pro",
        "writes" => "nova-writes"
    ];

    public function chat($message, $extended, $model, $userinfo, $files = [])
    {
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache, no-store");
        header("X-Accel-Buffering: no");

        while (ob_get_level() > 0){
            ob_end_clean();
        }

        // Automatically flush output
        ob_implicit_flush(enable: true);

        $model = strtolower($model);
        $extended = $extended == "true";

        if (!in_array(strtolower($model), self::AVAILABLE_MODELS)){
            return $this->errorResponse(
                code: 404,
                title: "Model not found", 
                message: "You can only use defined models listed in menu"
            );
        }

        if ($extended && $model === self::AVAILABLE_MODELS['default']){
            $temperature = 0.7;
            $topP = 0.6;
            $topK = 30;
        } 
        elseif (!$extended && $model === self::AVAILABLE_MODELS['default']){
            $temperature = 0.9;
            $topP = 0.7;
            $topK = 40;
        } 
        elseif ($extended && $model === self::AVAILABLE_MODELS['pro']){
            $temperature = 0.3;
            $topP = 0.3;
            $topK = 15;
        } 
        elseif (!$extended && $model === self::AVAILABLE_MODELS['pro']){
            $temperature = 0.5;
            $topP = 0.4;
            $topK = 25;
        }
        elseif ($extended && $model === self::AVAILABLE_MODELS['writes']){
            $temperature = 1.2;
            $topP = 0.8;
            $topK = 50;
        } 
        elseif (!$extended && $model === self::AVAILABLE_MODELS['writes']){
            $temperature = 1.7;
            $topP = 0.9;
            $topK = 80;
        }

        $instruction_parts = [
            ["text" => file_get_contents(view_path("instruction.md"))]
        ];
        
        $userinfo = json_decode($userinfo, associative: true);

        if (!empty($userinfo['fullname']) || !empty($userinfo['nickname'])) {
            $fullname = $userinfo['fullname'] ?? '';
            $nickname = $userinfo['nickname'] ?? '';

            if (!empty($fullname) && !empty($nickname)) {
                $userText = "their name is {$fullname}, and they prefer to be called {$nickname}";
                $addressNote = "When appropriate, address the user by their preferred name ({$nickname}) in a natural way.";
            } elseif (!empty($fullname)) {
                $userText = "their name is {$fullname}";
                $addressNote = "When appropriate, address the user by their name ({$fullname}) in a natural way.";
            } else {
                $userText = "they prefer to be called {$nickname}";
                $addressNote = "When appropriate, address the user by their preferred name ({$nickname}) in a natural way.";
            }

            $instruction_parts[]["text"] =
                "You already know this about the user: {$userText}. This is confirmed, reliable information — not a guess or assumption. When they ask about their name or how they should be addressed, answer directly and confidently without hedging. {$addressNote}";
        }

        $table = env("DB_TABLE");
        $contents = $this->processHistory(
            array_reverse(
                DB::table($table)
                    ->orderBy("id", "desc")
                    ->orderBy("datetime", "desc")
                    ->limit($this->historyLimit)
                    ->get(["message", "message_from"])
            )
        );

        $parsedFiles = $this->parseFiles($files);

        // append current user message in history
        $contents[] = $this->processRequest($message, $parsedFiles);
        

        $geminiRequestBody = [
            "system_instruction" => [
                "parts" => $instruction_parts
            ],
            "contents" => $contents,
            "generationConfig" => [
                "temperature" => $temperature,
                "topK" => $topK,
                "topP" => $topP,
                "maxOutputTokens" => 10000,
                "responseMimeType" => "text/plain",
                "candidateCount" => 1
            ],
            "safetySettings" => [
                [
                    "category" => "HARM_CATEGORY_HARASSMENT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_HATE_SPEECH",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_DANGEROUS_CONTENT",
                    "threshold" => "BLOCK_NONE"
                ],
            ]
        ];

        // error_log(json_encode($geminiRequestBody), 3, public_path("logs/check.log"));

        $response = Http::url("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:streamGenerateContent")
                ->contentType("application/json")
                ->setHeader("x-goog-api-key", env("GEMINI_API_KEY"))
                ->forceToFlushOutput()
                ->withBody($geminiRequestBody)
                ->connectTimeout(5)
                ->streamWriteFunction($this, "handleGeminiChunksStreaming")
                ->post();


        if ($response->isNetworkFailure()){
            return $this->errorResponse(
                code: 500,
                title: "Connection Timeout",
                message: $response->getError()
            );
        }

        if (strlen($this->errorBuffer) > 0){
            $error = json_decode($this->errorBuffer, associative: true);
            $errorMessage = $error['error']['message'] ?? $error; // can be null
            $statusCode = $error ? $error['error']['code'] : 429;

            $this->appendErrorLog($error ?? $this->errorBuffer);

            return $this->errorResponse(
                code: $statusCode,
                title: "Something went wrong",
                message: $errorMessage ?? "Server is facing some issue. Please try later"
            );
        }

        $filesPathName = [];
        foreach ($parsedFiles as $file){
            $filesPathName[] = $file['name'];
        }

        // save user message into DB
        $this->saveChatInDatabase($message, "user", $filesPathName);
        
        $resMessage = $this->fullBufferText;

        // save model response into DB
        $this->saveChatInDatabase($resMessage, "model");

        // update log's history
        $this->appendHistoryLog($message, from: "user");
        $this->appendHistoryLog($resMessage, from: "model");

        // save user's uploaded files
        $this->saveUploadedFiles($parsedFiles);

        return;
    }

    public function handleGeminiChunksStreaming($curl, $chunk)
    {
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200){
            $this->errorBuffer .= $chunk;
            return strlen($chunk);
        }

        $this->handleChunks($chunk);
        return strlen($chunk);
    }

    protected function handleChunks($jsonChunks)
    {
        $this->rawBuffer .= $jsonChunks;

        $len = strlen($this->rawBuffer);
        $i = 0;
        $depth = 0;
        $inString = false;
        $escape = false;
        $objStart = null;
        $consumedUpTo = 0;

        while ($i < $len) {
            $char = $this->rawBuffer[$i];

            if ($inString) {
                if ($escape) {
                    $escape = false;
                } elseif ($char === '\\') {
                    $escape = true;
                } elseif ($char === '"') {
                    $inString = false;
                }
                $i++;
                continue;
            }

            if ($char === '"') {
                $inString = true;
            }
            elseif ($char === '{') {
                if ($depth === 0) $objStart = $i;
                $depth++;
            }
            elseif ($char === '}') {
                $depth--;
                if ($depth === 0 && $objStart !== null) {
                    $objStr = substr($this->rawBuffer, $objStart, $i - $objStart + 1);
                    $this->generateOutputStream($objStr);
                    $consumedUpTo = $i + 1;
                    $objStart = null;
                }
            }

            $i++;
        }

        // Remove only consumed buffer from the rawBuffer
        if ($consumedUpTo > 0) {
            $this->rawBuffer = substr($this->rawBuffer, $consumedUpTo);
        }
    }

    protected function generateOutputStream(string $singleJsonObj)
    {
        $decoded = json_decode($singleJsonObj, true);
        if ($decoded !== null) {
            $text = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '';
            echo $text;
            $this->fullBufferText .= $text;
        }
    }

    protected function saveChatInDatabase($message, $from, $filesPath = [])
    {
        $table = env("DB_TABLE");
        DB::table($table)
            ->insert([
                "message" => $message,
                "message_from" => $from,
                "files" => json_encode($filesPath ?? [])
            ]);
    }

    protected function saveUploadedFiles(array $parsedFiles = [])
    {
        foreach ($parsedFiles as $file){
            if ($file['error'] === UPLOAD_ERR_OK){
                // File's name already update
                move_uploaded_file($file['tmp_name'], public_path("uploads/".$file['name']));
            }
        }
    }

    protected function processHistory(array $history)
    {
        if (empty($history)) return [];

        $contents = [];
        $content = [];
        foreach ($history as $msg){
            $content["role"] = $msg['message_from'];
            $content["parts"][]["text"] = $msg['message'];
            $contents[] = $content;
            $content = [];
        }

        return $contents;
    }

    protected function processRequest(string $message, array $parseFiles = [])
    {
        $content = [
            "role" => "user",
            "parts" => [
                ["text" => $message]
            ]
        ];

        foreach ($parseFiles as $file) {
            if ($file["error"] === UPLOAD_ERR_OK) {
                $fileData = file_get_contents($file['tmp_name']);

                if ($fileData !== false) {
                    $content["parts"][] = [
                        "inlineData" => [
                            "mimeType" => $file['type'],
                            "data" => base64_encode($fileData)
                        ]
                    ];
                }
            }
        }

        return $content;
    }

    protected function parseFiles(array $files)
    {
        $arrFiles = [];

        foreach ($files as $key => $arr){
            for ($i = 0; $i < count($arr); $i++){
                if ($key === "name"){
                    $filename = pathinfo($arr[$i], PATHINFO_FILENAME) . "-" . rand(10000, 99999);
                    $ext = pathinfo($arr[$i], PATHINFO_EXTENSION);
                    $arrFiles[$i]["name"] = str_replace(" ", "-", $filename . "." . $ext);
                }
                else{
                    $arrFiles[$i][$key] = $arr[$i];
                }
            }
        }

        return $arrFiles;
    }

    public function fallback()
    {
        return $this->errorResponse(
            code: 404,
            title: "URL Not Found",
            message: "Invalid URL or request method."
        );
    }
}

?>