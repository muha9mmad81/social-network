<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmailNotificationPreferences extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'activity_mention',
        'activity_replies',
        'message',
        'membership_invitation',
        'send_friend_request',
        'accept_friend_request',
        'group_invitation',
        'group_info_update',
        'group_administrator_mod',
        'join_private_group',
        'group_request',
    ];
}
