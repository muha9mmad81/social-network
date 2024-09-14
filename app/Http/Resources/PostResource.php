<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'id'                    => $this->id ?? null,
            'title'                 => $this->title ?? null,
            'type'                 => $this->type ?? null,
            'description'           => $this->description ?? null,
            'user'                  =>  $this->user ? new UserResource($this->user) : null,
            'images'                => $this->images ? PostImageResource::collection($this->images) : null,
            'videos'                => $this->videos ? PostVideoResource::collection($this->videos) : null,
            'comments'              => $this->comments ? PostCommentResource::collection($this->comments) : null,
            'likes_count'           => $this->likes()->count(), 
            'dislikes_count'        => $this->dislikes()->count(),
            'created_at'            => $this->created_at ? Carbon::parse($this->created_at)->diffForHumans() : null,
        ];
    }
}
