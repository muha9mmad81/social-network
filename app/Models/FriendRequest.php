<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FriendRequest extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    // Sender Relationship
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Receiver Relationship
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function sendFriendRequest(Request $request)
    {
        try {
            $receiverId = $request->reciever_id;
            $friendRequest = $this->firstOrCreate([
                'sender_id' => auth()->id(),
                'receiver_id' => $receiverId,
            ]);

            return response()->json(['status' => 200, 'message' => 'Friend Request sent successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }

    public function respondToFriendRequest(Request $request)
    {
        try {
            $status = $request->status;
            $friendRequest = $this->findOrFail($request->friendRequestId);

            if ($status == 'accepted') {
                // Add to friends
                $friendRequest->sender->friends()->attach($friendRequest->receiver_id);
                $friendRequest->receiver->friends()->attach($friendRequest->sender_id);
            }

            $friendRequest->update(['status' => $status]);

            return response()->json(['status' => 200, 'message' => 'Friend Request ' . $status . ' successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occured, ' . $e->getMessage(), 'status' => 401], 401);
        }
    }
}
