<?php

namespace App\Models;

use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Friend extends Model
{
    use HasFactory;

    public function getMyFriendsList(Request $request)
    {
        try {
            return response()->json(['status' => 200, 'data' => UserResource::collection(auth()->user()->friends)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
