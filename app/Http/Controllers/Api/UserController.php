<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user, $friendRequest, $friend, $group;
    function __construct(User $user, FriendRequest $friendRequest, Friend $friend)
    {
        $this->user = $user;
        $this->friendRequest = $friendRequest;
        $this->friend = $friend;
    }

    public function editUserProfile(Request $request)
    {
        return $this->user->editUserProfile($request);
    }

    public function updateProfilePhoto(Request $request)
    {
        return $this->user->updateProfilePhoto($request);
    }

    public function updateCoverPhoto(Request $request)
    {
        return $this->user->updateCoverPhoto($request);
    }

    public function getAllUsers(Request $request)
    {
        return $this->user->getAllUsers($request);
    }

    public function showFriendRequests(Request $request)
    {
        return $this->user->showFriendRequests($request);
    }

    public function sendFriendRequest(Request $request)
    {
        return $this->friendRequest->sendFriendRequest($request);
    }

    public function respondToFriendRequest(Request $request)
    {
        return $this->friendRequest->respondToFriendRequest($request);
    }

    public function getMyFriendsList(Request $request)
    {
        return $this->friend->getMyFriendsList($request);
    }

    public function getUserDetail(Request $request, $userId)
    {
        return $this->user->getUserDetail($request, $userId);
    }
}
