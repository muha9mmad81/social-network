<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCompanyResource extends JsonResource
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
            'website'         => $this->website ?? null,
            'tagline'    =>  $this->tagline ?? null,
            'video'       => $this->video ?? null,
            'twitter_username'       => $this->twitter_username ?? null,
            'logo' => $this->logo ? asset('images/jobs/' . $this->logo) : null,
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
