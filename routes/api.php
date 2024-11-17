<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    // User Routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Post Routes
    Route::get('/posts', [PostController::class, 'index']); // Get all posts
    Route::post('/posts', [PostController::class, 'store']); // Create post
    Route::get('/posts/{id}', [PostController::class, 'show']); // Get single post
    Route::put('/posts/{id}', [PostController::class, 'update']); // Update post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // Delete post

    // Comment Routes
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']); // Get comments for a post
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']); // Add comment to a post
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // Delete comment

    // Like Routes
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnLike']);
     


});
