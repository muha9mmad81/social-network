<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'user_id', 'owner'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addGroupMember($member_id, $groupId, $owner = 0)
    {
        $groupMember = new self();
        $group = $groupMember->where('group_id', $groupId)->where('user_id', $member_id)->first();
        if(!$group){
            $groupMember->group_id = $groupId;
            $groupMember->user_id = $member_id;
            $groupMember->owner = $owner;
    
            $groupMember->save();
        }
    }

    public static function removeGroupMember($member_id, $groupId)
    {
        $groupMember = new self();
        $groupMember->where('group_id', $groupId)->where('user_id', $member_id)->delete();
    }
}
