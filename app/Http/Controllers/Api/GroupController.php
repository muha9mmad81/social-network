<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $group;
    function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function createGroup(CreateGroupRequest $request)
    {
        return $this->group->createGroup($request);
    }

    public function joinGroup(Request $request)
    {
        return $this->group->joinGroup($request);
    }

    public function leaveGroup(Request $request)
    {
        return $this->group->leaveGroup($request);
    }

    public function getAllGroups(Request $request)
    {
        return $this->group->getAllGroupDetails($request);
    }

    public function getMyGroups(Request $request)
    {
        return $this->group->getMyGroupDetails($request);
    }
}
