<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailPreferenceResource extends JsonResource
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
            'activity_mention'           => $this->activity_mention ?? null,
            'activity_replies'           => $this->activity_replies ?? null,
            'message'                    => $this->message ?? null,
            'membership_invitation'       => $this->membership_invitation ?? null,
            'send_friend_request'         => $this->send_friend_request ?? null,
            'accept_friend_request'       => $this->accept_friend_request ?? null,
            'group_invitation'            => $this->group_invitation ?? null,
            'group_info_update'           => $this->group_info_update ?? null,
            'group_administrator_mod'     => $this->group_administrator_mod ?? null,
            'join_private_group'          => $this->join_private_group ?? null,
            'group_request'               => $this->group_request ?? null,
        ];
    }
}
