<?php

namespace App\Controller;

use DateTime;

class Controller {
    const ERROR_LOG_PATH = "logs/error.log";

    const HISTORY_LOG_PATH = "logs/history.log";

    protected function response(int $code, ?string $title = null, ?string $message = null, mixed $data = null): string
    {
        http_response_code($code);
        $response = compact("code");

        if ($title !== null) $response['title'] = $title;
        if ($message !== null) $response['message'] = $message;
        if ($data !== null) $response['data'] = $data;

        return json_encode($response);
    }

    protected function successResponse(int $code, mixed $data): string
    {
        return $this->response(code: $code, data: $data);
    }

    protected function errorResponse(int $code, string $title, string $message): string
    {
        return $this->response(code: $code, title: $title, message: $message);
    }

    protected function appendHistoryLog($message, $from = null): void
    {
        $this->appendLog($message, error: false, from: $from);
    }

    protected function appendErrorLog($message): void
    {
        $this->appendLog($message, error: true);
    }

    private function appendLog($message, bool $error, ?string $from = null): void
    {
        $datetime = new DateTime();
        $modelLine = $from ? " --- (". strtoupper($from) .")" : "";

        error_log(
            message: "(" . $datetime->format("Y-m-d H:i:s") . ")$modelLine\n" . $message . "\n\n\n", 
            message_type: 3,
            destination: public_path($error ? self::ERROR_LOG_PATH : self::HISTORY_LOG_PATH)
        );
    }
}
