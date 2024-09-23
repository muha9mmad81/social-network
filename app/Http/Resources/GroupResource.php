<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'description'         => $this->description ?? null,
            'privacy'    =>  $this->privacy ?? null,
            'invitation'    =>  $this->invitation ?? null,
            'forum'    =>  $this->forum ?? null,
            'album'    =>  $this->album ?? null,
            'image' => $this->image ? asset('images/groups/' . $this->image) : null,
            'cover_image' => $this->cover_image ? asset('images/groups/' . $this->cover_image) : null,
            'owner'    =>  $this->owner ? new UserResource($this->owner) : null,
            'members'    =>  $this->members ? GroupMemberResource::collection($this->members) : null,
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
