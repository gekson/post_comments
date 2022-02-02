<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "/",
    "namespace" => "Post",
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('/add', [PostController::class, 'create']);
    Route::put('/update/{id}', [PostController::class, 'update']);
    Route::get('/get', [PostController::class, 'get']);
    Route::delete('/delete/{id}', [PostController::class, 'delete']);
});

Route::group([
    'prefix' => "/",
    "namespace" => "Post"
], function () {
    Route::get('/find/{id}', [PostController::class, 'find']);
});
