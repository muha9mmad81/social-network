<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'          => $this->name ?? null,
            'email'         => $this->email ?? null,
            'username'    =>  $this->username ?? null,
            'user_information'    =>  $this->user_information ? new UserInformationResource($this->user_information) : null,
            'is_online'       => $this->is_online ?? false,
            'last_seen'       => $this->last_seen ? $this->last_seen->diffForHumans() : null, 
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
