<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\GroupController;
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
Route::post('/activate-account', [AuthController::class, 'activateYourAccount'])->name('activate-account');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::get('/get-all-posts', [PostController::class, 'getAllPosts'])->name('get-all-posts');
Route::get('/get-post/{id}', [PostController::class, 'getSinglePost'])->name('get-post');
Route::get('/get-all-groups', [GroupController::class, 'getAllGroups'])->name('get-all-groups');
Route::get('/user-online-status/{userId}', [AuthController::class, 'getUserOnlineStatus'])->name('user-online-status');
Route::get('/get-user-detail/{userId}', [UserController::class, 'getUserDetail'])->name('get-user-detail');
Route::get('/get-group-posts', [PostController::class, 'getAllGroupPosts'])->name('get-group-posts');
Route::get('/get-posts-according-to-group', [PostController::class, 'getPostAccordingToGroup'])->name('get-posts-according-to-group');

Route::get('/get-user-posts/{userId}', [PostController::class, 'getUserPosts'])->name('get-user-posts');
Route::get('/user-media/{userId}', [PostController::class, 'getUserMediaWithoutAuthentication'])->name('get-user-media-by-userid');
Route::get('/get-user-favourite-posts/{userId}', [PostController::class, 'getUserFavouritePosts'])->name('get-user-favourite-posts');
Route::get('/get-user-group-posts/{userId}', [PostController::class, 'getAllUserGroupPosts'])->name('get-user-group-posts');
Route::get('/get-my-mentioned-posts/{userId}', [PostController::class, 'getMyMentionedPosts'])->name('get-my-mentioned-posts');
Route::get('/get-user-friends/{userId}', [UserController::class, 'getUserFriendsList'])->name('get-user-friends');
Route::get('/get-my-friends-posts/{userId}', [UserController::class, 'getMyFriendsPosts'])->name('get-my-friends-posts');
Route::get('/get-user-groups/{userId}', [GroupController::class, 'getUserGroups'])->name('get-user-groups');

Route::get('/get-all-jobs', [UserController::class, 'getAllJobs'])->name('get-all-jobs');
Route::get('/get-single-job/{jobId}', [UserController::class, 'getJobById'])->name('get-single-job');

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout-user', [AuthController::class, 'logoutUser'])->name('logout-user');

    Route::post('/create-post', [PostController::class, 'createPost'])->name('create-post');
    Route::delete('/delete-post/{postId}', [PostController::class, 'deletePost'])->name('delete-post');
    Route::get('/get-my-posts', [PostController::class, 'getMyPosts'])->name('get-my-posts');
    Route::post('/add-post-comment', [PostController::class, 'addPostComment'])->name('add-post-comment');
    Route::post('/edit-post-comment/{commentId}', [PostController::class, 'editPostComment'])->name('edit-post-comment');
    Route::delete('/delete-post-comment/{commentId}', [PostController::class, 'deletePostComment'])->name('delete-post-comment');
    Route::post('/post-likes-and-dislikes', [PostController::class, 'addLikesAndDislikesToPosts'])->name('post-likes-and-dislikes');
    Route::post('/comment-likes-and-dislikes', [PostController::class, 'addLikesAndDislikesToComments'])->name('comment-likes-and-dislikes');
    Route::get('/user/{id}/media', [PostController::class, 'getUserMedia'])->name('get-user-media');
    Route::post('/create-remove-post-favourite', [PostController::class, 'createOrRemovePostFavourite'])->name('create-remove-post-favourite');
    Route::get('/get-my-favourite-post', [PostController::class, 'getMyFavouritePosts'])->name('get-my-favourite-post');
    Route::get('/get-my-group-posts', [PostController::class, 'getAllMyGroupPosts'])->name('get-my-group-posts');
    Route::post('/share-post/{postId}', [PostController::class, 'sharePost'])->name('share-post');

    Route::post('/edit-profile', [UserController::class, 'editUserProfile'])->name('edit-profile');
    Route::post('/edit-email-preference', [UserController::class, 'editUserEmailPreference'])->name('edit-email-preference');
    Route::post('/update-profile-photo', [UserController::class, 'updateProfilePhoto'])->name('update-profile-photo');
    Route::post('/update-cover-photo', [UserController::class, 'updateCoverPhoto'])->name('update-cover-photo');
    Route::get('/get-all-users', [UserController::class, 'getAllUsers'])->name('get-all-users');
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update-password');

    Route::post('/send-invitation', [UserController::class, 'sendInvitation'])->name('send-invitation');
    Route::post('/update-invitation-status', [UserController::class, 'updateInvitationStatus'])->name('update-invitation-status');
    Route::get('/get-my-invitations', [UserController::class, 'getMyInvitations'])->name('get-my-invitations');

    Route::post('/send-friend-request', [UserController::class, 'sendFriendRequest'])->name('send-friend-request');
    Route::post('/respond-friend-request', [UserController::class, 'respondToFriendRequest'])->name('respond-friend-request');
    Route::get('/get-my-friends', [UserController::class, 'getMyFriendsList'])->name('get-my-friends');
    Route::get('/get-my-friend-requests', [UserController::class, 'showFriendRequests'])->name('get-my-friend-requests');
    
    
    Route::post('/post-job', [UserController::class, 'postJob'])->name('post-job');
    Route::get('/get-my-jobs', [UserController::class, 'getMyJobs'])->name('get-my-jobs');


    Route::post('/chat', [ChatController::class, 'message'])->name('chat');
    Route::get('/get-all-conversation', [ChatController::class, 'getAllConversation'])->name('get-all-conversation');
    Route::get('/get-user-converstaions-list', [ChatController::class, 'getAllConversationsListWithLastMessage'])->name('get-user-converstaions-list');

    Route::post('/create-group', [GroupController::class, 'createGroup'])->name('create-group');
    Route::post('/join-group', [GroupController::class, 'joinGroup'])->name('join-group');
    Route::post('/leave-group', [GroupController::class, 'leaveGroup'])->name('leave-group');
    Route::get('/get-my-groups', [GroupController::class, 'getMyGroups'])->name('get-my-groups');
});
