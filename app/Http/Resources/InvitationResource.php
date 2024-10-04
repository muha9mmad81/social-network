<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id ?? null,
            'status'          => $this->status ?? null,
            'sender'    =>  $this->sender ? new UserResource($this->sender) : null,
            'reciever'    =>  $this->reciever ? new UserResource($this->reciever) : null,
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
