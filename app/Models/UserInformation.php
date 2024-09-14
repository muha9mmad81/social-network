<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dob',
        'dob_visibility',
        'gender',
        'gender_visibility',
        'city',
        'city_visibility',
        'country',
        'country_visibility',
        'profile_image',
        'cover_image',
        'about',
        'about_visibility',
        'link1',
        'link1_visibility',
        'link2',
        'link2_visibility'
    ];
}
