<?php 

namespace App\Controller;

use BuildQL\Database\Query\DB;

class HistoryController extends Controller{
    public function getHistory()
    {
        $chats = DB::table(env("DB_TABLE"))
                    ->get();
        
        $history = [];
        foreach ($chats as $chat){
            $chat['files'] = json_decode($chat['files'], associative: true) ?? [];

            if (!empty($chat['files'])){
                $filesUrl = [];    

                foreach ($chat['files'] as $fileName){
                    $fileUrl = env('BASE_URL') . "/public/uploads/" . $fileName;

                    if (file_exists(public_path("uploads/".$fileName))){
                        $filesUrl[] = $fileUrl;
                    }
                }
                
                $chat['files'] = $filesUrl;
            }

            $history[] = $chat;
        }

        return $this->successResponse(code: 200, data: $history);
    }

    public function deleteHistory($delete_id)
    {
        if (!session()->has("delete_id") || session()->get("delete_id") !== $delete_id){
            return $this->errorResponse(
                code: 400,
                title: "Invalid Delete Request",
                message: "You can't perform delete operation because of invalid delete ID"
            );
        }
        
        session()->delete("delete_id");

        $table = DB::table(env("DB_TABLE"));
        $chats = $table->get();

        foreach ($chats as $chat){
            $files = json_decode($chat['files'], associative: true) ?? [];

            if (!empty($files)){
                foreach ($files as $fileName){
                    $filePath = public_path("uploads/" . $fileName);

                    if (file_exists($filePath)){
                        unlink($filePath);
                    }
                }
            }
        }

        $table->whereNotNull("id")->delete();

        $this->appendHistoryLog(
            "User perform delete operation and delete his all entire chat history.\n\n-----------------------------------------------------"
        );

        return $this->response(
            code: 200,
            title: "Deleted Successfully",
            message: "Your chats successfully deleted."
        );
    }

    public function deleteSessionID()
    {
        $sessionID = bin2hex(random_bytes(16));
        session([
            "delete_id" => $sessionID
        ]);

        return $this->response(code: 200, data: ["delete_id" => $sessionID]);
    }
}

?>