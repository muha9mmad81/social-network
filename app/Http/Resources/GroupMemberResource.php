<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupMemberResource extends JsonResource
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
            'member'          => $this->user ? new UserResource($this->user) : null,
            'owner'         => $this->owner == 1 ? 'Owner' : 'Member',
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
