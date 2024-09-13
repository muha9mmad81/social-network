<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
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
            'id'  => $this->id,
            'comment' => $this->comment,
            'likes_count'           => $this->likes()->count(), 
            'dislikes_count'        => $this->dislikes()->count(),
            'user' => $this->user ? new UserResource($this->user) : null,
            'replies' => $this->replies ? PostCommentResource::collection($this->replies) : null,
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->diffForHumans() : null,
        ];
    }
}
