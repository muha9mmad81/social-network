<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'title'          => $this->title ?? null,
            'description'         => $this->description ?? null,
            'location'    =>  $this->location ?? null,
            'user'    =>  $this->user ? new UserResource($this->user) : null,
            'job_type'       => $this->job_type ?? null,
            'remote'       => $this->remote == 1 ? 'Remote' : 'Onsite',
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
