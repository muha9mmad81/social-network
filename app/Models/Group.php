<?php

namespace App\Models;

use App\Http\Resources\GroupResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'cover_image', 'privacy', 'invitation', 'forum', 'album', 'created_by'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function createGroup(Request $request)
    {
        try {
            $this->name = $request->name;
            $this->description = $request->description;
            $this->privacy = $request->privacy;
            $this->invitation = $request->invitation;
            $this->forum = $request->forum;
            $this->album = $request->album;
            $this->created_by = auth()->user()->id;

            if ($request->image) {
                $fileName = $request->image->getClientOriginalName();
                $image = saveFile($request->image, 'images/groups', $fileName);
                $this->image = $image['name'];
            }

            if ($request->cover_image) {
                $fileName = $request->cover_image->getClientOriginalName();
                $cover_image = saveFile($request->cover_image, 'images/groups', $fileName);
                $this->cover_image = $cover_image['name'];
            }

            $this->save();
            GroupMember::addGroupMember(auth()->user()->id, $this->id, 1);
            foreach ($request->members as $member) {
                GroupMember::addGroupMember($member, $this->id);
            }

            return response()->json(['message' => 'Group created successfully.', 'status' => 200, 'data' => new GroupResource($this)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getAllGroupDetails(Request $request)
    {
        try {
            $groups = $this->orderByDesc('id')->get();

            $collection = GroupResource::collection($groups);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function getMyGroupDetails(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $groupIds = GroupMember::where('user_id', $userId)->distinct()->pluck('group_id');
            $groups = $this->whereIn('id', $groupIds)->orderByDesc('id')->get();
            $collection = GroupResource::collection($groups);

            return response()->json(['status' => 200, 'data' => $collection], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function joinGroup(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            GroupMember::addGroupMember($userId, $request->group_id, 0);
            $group = $this->find($request->group_id);

            return response()->json(['status' => 200, 'data' => new GroupResource($group), 'message' => 'You have joined the group successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function leaveGroup(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            GroupMember::removeGroupMember($userId, $request->group_id);

            return response()->json(['status' => 200, 'message' => 'You have left the group successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
