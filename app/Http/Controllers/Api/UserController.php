<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationSendRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Invitation;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user, $friendRequest, $friend, $group, $invitation, $job;
    function __construct(User $user, FriendRequest $friendRequest, Friend $friend, Invitation $invitation, Job $job)
    {
        $this->user = $user;
        $this->friendRequest = $friendRequest;
        $this->friend = $friend;
        $this->invitation = $invitation;
        $this->job = $job;
    }

    public function editUserProfile(Request $request)
    {
        return $this->user->editUserProfile($request);
    }

    public function editUserEmailPreference(Request $request)
    {
        return $this->user->editUserEmailPreference($request);
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

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        return $this->user->updatePassword($request);
    }

    public function sendInvitation(InvitationSendRequest $request)
    {
        return $this->invitation->sendInvitation($request);
    }

    public function updateInvitationStatus(Request $request)
    {
        return $this->invitation->updateInvitationStatus($request);
    }

    public function getMyInvitations(Request $request)
    {
        return $this->invitation->getMyInvitations($request);
    }

    public function getUserFriendsList(Request $request, $userId)
    {
        return $this->friend->getUserFriendsList($request, $userId);
    }

    public function getMyFriendsPosts(Request $request, $userId)
    {
        return $this->user->getMyFriendsPosts($request, $userId);
    }

    public function postJob(Request $request)
    {
        return $this->job->postJob($request);
    }

    public function getMyJobs(Request $request)
    {
        return $this->job->getMyJobs($request);
    }

    public function getAllJobs(Request $request)
    {
        return $this->job->getAllJobs($request);
    }

    public function getJobById(Request $request, $jobId)
    {
        return $this->job->getJobById($request, $jobId);
    }
}
