<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserTokenResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user' => new AuthUserResource( $request->user() ),
            'token' => $this->token_type. ' ' .$this->access_token,
            'expires' => $this->expires_at,
        ];
    }
}
