<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "/",
    "namespace" => "Comment",
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('/', [CommentController::class, 'create']);
    Route::put('{id}', [CommentController::class, 'update']);
    Route::delete('/{id}', [CommentController::class, 'delete']);
});

Route::group([
    'prefix' => "/",
    "namespace" => "Comment"
], function () {
    Route::get('/', [CommentController::class, 'get']);
    Route::get('/find/{id}', [CommentController::class, 'find']);
});

