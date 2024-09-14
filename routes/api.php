<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify-code');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::get('/get-all-posts', [PostController::class, 'getAllPosts'])->name('get-all-posts');
Route::get('/get-post/{id}', [PostController::class, 'getSinglePost'])->name('get-post');

Route::middleware(['auth:api'])->group(function () {
    Route::post('/create-post', [PostController::class, 'createPost'])->name('create-post');
    Route::delete('/delete-post/{postId}', [PostController::class, 'deletePost'])->name('delete-post');
    Route::get('/get-my-posts', [PostController::class, 'getMyPosts'])->name('get-my-posts');
    Route::post('/add-post-comment', [PostController::class, 'addPostComment'])->name('add-post-comment');
    Route::post('/edit-post-comment/{commentId}', [PostController::class, 'editPostComment'])->name('edit-post-comment');
    Route::delete('/delete-post-comment/{commentId}', [PostController::class, 'deletePostComment'])->name('delete-post-comment');
    Route::post('/post-likes-and-dislikes', [PostController::class, 'addLikesAndDislikesToPosts'])->name('post-likes-and-dislikes');
    Route::post('/comment-likes-and-dislikes', [PostController::class, 'addLikesAndDislikesToComments'])->name('comment-likes-and-dislikes');

    Route::post('/edit-profile', [UserController::class, 'editUserProfile'])->name('edit-profile');
    Route::post('/update-profile-photo', [UserController::class, 'updateProfilePhoto'])->name('update-profile-photo');
    Route::post('/update-cover-photo', [UserController::class, 'updateCoverPhoto'])->name('update-cover-photo');
});
