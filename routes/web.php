<?php

use App\Controller\ChatController;
use App\Controller\HistoryController;
use App\Support\Route;

Route::view("/", "index.php")->generate();

Route::post("/api/chat", [ChatController::class, "chat"])
    ->name("chat")->generate();

Route::get("/api/history", [HistoryController::class, "getHistory"])
    ->name("history")->generate();

Route::delete("/api/delete", [HistoryController::class, "deleteHistory"])
    ->name("delete")->generate();

Route::get("/api/generate/delete-id", [HistoryController::class, "deleteSessionID"])
    ->name("generate.delete.id")->generate();

Route::fallback([ChatController::class, "fallback"])
    ->generate();


?>