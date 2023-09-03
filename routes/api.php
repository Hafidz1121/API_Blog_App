<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostsController;
use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\LikesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// For User
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('logout', [AuthController::class, 'logout']);
Route::post('save_user', [AuthController::class, 'saveUser'])->middleware('jwtAuth');

// For Post
Route::get('posts/my_post', [PostsController::class, 'myPosts'])->middleware('jwtAuth');
Route::get('posts', [PostsController::class, 'posts'])->middleware('jwtAuth');
Route::post('posts/create', [PostsController::class, 'create'])->middleware('jwtAuth');
Route::post('posts/update', [PostsController::class, 'update'])->middleware('jwtAuth');
Route::post('posts/delete', [PostsController::class, 'delete'])->middleware('jwtAuth');

// For Comment
Route::post('posts/comments', [CommentsController::class, 'comments'])->middleware('jwtAuth');
Route::post('comments/create', [CommentsController::class, 'create'])->middleware('jwtAuth');
Route::post('comments/update', [CommentsController::class, 'update'])->middleware('jwtAuth');
Route::post('comments/delete', [CommentsController::class, 'delete'])->middleware('jwtAuth');

// For Like
Route::post('posts/like', [LikesController::class, 'like'])->middleware('jwtAuth');