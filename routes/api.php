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


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function(){
    //user
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    

    //post 
    Route::get('/posts', [PostController::class, 'index']); //all posts
    Route::post('/posts', [PostController::class, 'store']); //create posts
    Route::get('/posts/{id}', [PostController::class, 'show']); //get single post
    Route::put('/posts/{id}', [PostController::class, 'update']); //update posts
    Route::delete('/posts{id}', [PostController::class, 'destroy']); //all posts

     //Comments
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']); // all comments for a post
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']); // add a comment
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // delete a comment

    //Likes
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnLike']); // like/unlike a post
     


});
