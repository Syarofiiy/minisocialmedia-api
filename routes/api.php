<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\MessageController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;

Route::prefix('v1')->group(function() {

    Route::post('register', [JWTAuthController::class, 'register']);
    Route::post('login', [JWTAuthController::class, 'login']);

    Route::middleware(JWTMiddleware::class)->prefix('/posts')->group(function() {
        Route::get('/', [PostsController::class, 'index']);
        Route::post('/', [PostsController::class, 'store']);
        Route::get('{id}', [PostsController::class, 'show']);
        Route::put('{id}', [PostsController::class, 'update']);
        Route::delete('{id}', [PostsController::class, 'destroy']);
    });

    Route::middleware(JWTMiddleware::class)->prefix('/comments')->group(function() {
        Route::post('/', [CommentsController::class, 'store']);
        Route::delete('{id}', [CommentsController::class, 'destroy']);
    });

    Route::middleware(JWTMiddleware::class)->prefix('/likes')->group(function() {
        Route::post('/', [LikesController::class, 'store']);
        Route::delete('{id}', [LikesController::class, 'destroy']);
    });

    Route::middleware(JWTMiddleware::class)->prefix('/messages')->group(function() {
        Route::post('/', [MessageController::class, 'store']);
        Route::get('{id}', [MessageController::class, 'show']);
        Route::get('/getMessages/{user_id}', [MessageController::class, 'getMessages']);
        Route::delete('{id}', [MessageController::class, 'destroy']);
    });
});
