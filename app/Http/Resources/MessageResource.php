<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currentTime = Carbon::now();
        $createdAt = Carbon::parse($this->created_at);
        $sent_time = $createdAt->diffForHumans(null, true) . ' ago';
        return [
            'id' => $this->id,
            'message' => $this->message ? $this->message : null,
            'attachment' => $this->message[0]->attachment ? asset('images/messages/' . $this->message[0]->attachment) : null,
            'sender' => $this->from_id ? new UserResource($this->sender) : null,
            'reciever' => $this->to_id ? new UserResource($this->reciever) : null,
            'sent_time' => $sent_time ?? null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
