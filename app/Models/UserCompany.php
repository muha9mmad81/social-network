<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'name', 'website', 'tagline', 'video', 'twitter_username', 'logo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
