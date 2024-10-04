<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInformationResource extends JsonResource
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
            'profile_visibiltiy'                 => $this->profile_visibiltiy ?? null,
            'dob'                 => $this->dob ?? null,
            'dob_visibility'           => $this->dob_visibility ?? null,
            'gender'           => $this->gender ?? null,
            'gender_visibility'           => $this->gender_visibility ?? null,
            'city'           => $this->city ?? null,
            'city_visibility'           => $this->city_visibility ?? null,
            'country'           => $this->country ?? null,
            'country_visibility'           => $this->country_visibility ?? null,
            'about'           => $this->about ?? null,
            'about_visibility'           => $this->about_visibility ?? null,
            'group_invite'           => $this->group_invite ?? null,
            'link1'           => $this->link1 ?? null,
            'link1_visibility'           => $this->link1_visibility ?? null,
            'link2'           => $this->link2 ?? null,
            'link2_visibility'           => $this->link2_visibility ?? null,
            'profile_image' => $this->profile_image ? asset('images/users/' . $this->profile_image) : null,
            'cover_image' => $this->cover_image ? asset('images/users/' . $this->cover_image) : null,
        ];
    }
}
