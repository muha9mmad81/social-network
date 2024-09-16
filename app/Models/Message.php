<?php

namespace App\Models;

use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Message extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const SEEN = 1;
    const DELIEVERED = 0;

    public function conversation()
    {
        return $this->hasMany(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendMessageToUser(Request $request, $conversation)
    {
        $senderId = auth()->user()->id;
        $this->user_id = $senderId;
        $this->conversation_id = $conversation->id;
        $this->message = $request->message ?? null;

        if ($request->attachment) {
            $fileName = $request->attachment->getClientOriginalName();
            $file = saveFile($request->attachment, 'images/messages', $fileName);
            $this->attachment = $file['name'];
        }
        $this->save();
        // event(new MessageEvent($request->message, $senderId, $request->reciever_id));

        $conversations = Conversation::with('message')->find($conversation->id);
        $collectionData = new MessageResource($conversations);
        return response()->json(['message' => 'Message sent successfully.', 'data' => $collectionData, 'status' => 200], 200);
    }

    public function getAllConversation($sender_id, $reciever_id)
    {
        $conversations = Conversation::with(['message' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->where(function ($query) use ($sender_id, $reciever_id) {
            $query->where('from_id', $sender_id)
                ->where('to_id', $reciever_id);
        })->orWhere(function ($query) use ($sender_id, $reciever_id) {
            $query->where('from_id', $reciever_id)
                ->where('to_id', $sender_id);
        })->get();

        $data = MessageResource::collection($conversations);
        return response()->json(['data' => $data, 'status' => 200], 200);
    }
}
