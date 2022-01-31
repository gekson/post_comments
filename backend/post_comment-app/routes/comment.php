<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "/",
    "namespace" => "Comment",
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('/add', [CommentController::class, 'create']);
    Route::put('/update/{id}', [CommentController::class, 'update']);
    Route::get('/get', [CommentController::class, 'get']);
    Route::delete('/delete/{id}', [CommentController::class, 'delete']);
});

