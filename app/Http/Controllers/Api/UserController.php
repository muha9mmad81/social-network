<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;
    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function editUserProfile(Request $request){
        return $this->user->editUserProfile($request);
    }

    public function updateProfilePhoto(Request $request){
        return $this->user->updateProfilePhoto($request);
    }

    public function updateCoverPhoto(Request $request){
        return $this->user->updateCoverPhoto($request);
    }

    public function getAllUsers(Request $request) {
        return $this->user->getAllUsers($request);
    }
}
