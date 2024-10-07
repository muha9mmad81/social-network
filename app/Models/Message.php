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


    public function getAllConversationsListWithLastMessage($userId)
    {
        // Get all conversations for the user
        $conversations = Conversation::where(function ($query) use ($userId) {
            $query->where('from_id', $userId)
                ->orWhere('to_id', $userId);
        })
            ->with('message') // Eager load messages to avoid N+1 issue
            ->get()
            ->map(function ($conversation) use ($userId) {
                // Identify the other participant in the conversation
                $otherUserId = $conversation->from_id === $userId ? $conversation->to_id : $conversation->from_id;
                $lastMessage = $conversation->message()->latest()->first();

                return [
                    'conversation_id' => $conversation->id,
                    'user' => $conversation->from_id === $userId ? $conversation->reciever : $conversation->sender,
                    'last_message' => $lastMessage ? $lastMessage->message : null,
                    'last_message_time' => $lastMessage ? $lastMessage->created_at : null,
                    'other_user_id' => $otherUserId // Correctly get the other participant's ID
                ];
            })
            ->groupBy('other_user_id') // Group by the other user ID to handle unique users
            ->map(function ($group) {
                return collect($group)->sortByDesc('last_message_time')->first(); // Get the latest message for each user
            })
            ->values(); // Reset keys to have a clean array

        return $conversations;
    }

    // public function getAllConversationsListWithLastMessage($userId)
    // {
    //     // Get all conversations for the user
    //     $conversations = Conversation::where(function ($query) use ($userId) {
    //         $query->where('from_id', $userId)
    //             ->orWhere('to_id', $userId);
    //     })
    //         ->with('message') // Eager load messages to avoid N+1 issue
    //         ->get()
    //         ->map(function ($conversation) use ($userId) {
    //             $lastMessage = $conversation->message()->latest()->first();
    //             return [
    //                 'conversation_id' => $conversation->id,
    //                 'user' => $conversation->from_id === $userId ? $conversation->reciever : $conversation->sender,
    //                 'last_message' => $lastMessage ? $lastMessage->message : null,
    //                 'last_message_time' => $lastMessage ? $lastMessage->created_at : null,
    //                 'user_id' => $conversation->from_id === $userId ? $conversation->to_id : $conversation->from_id // Get the corresponding user ID
    //             ];
    //         })
    //         ->groupBy('user_id') // Group by the user ID to get unique users
    //         ->map(function ($group) {
    //             return collect($group)->sortByDesc('last_message_time')->first(); // Get the latest message for each user
    //         })
    //         ->values(); // Reset keys to have a clean array

    //     return $conversations;
    // }
}
