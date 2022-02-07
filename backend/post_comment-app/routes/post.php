<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "/",
    "namespace" => "Post",
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('/', [PostController::class, 'create']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'delete']);
});

Route::group([
    'prefix' => "/",
    "namespace" => "Post"
], function () {
    Route::get('/', [PostController::class, 'getWithRelationships']);
    Route::get('/find/{id}', [PostController::class, 'show']);
    Route::get('/findWithRelationship/{id}', [PostController::class, 'findWithRelationships']);
    Route::get('/like/{id}', [PostController::class, 'like']);
    Route::get('/dislike/{id}', [PostController::class, 'dislike']);
});
