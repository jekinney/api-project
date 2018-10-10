<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AuthUserResource extends Resource
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
            'name' => $this->name,
            'email' => $this->email,
            'roles' => RoleResource::collection( $this->limitedRoles ),
            'permissions' => PermissionResource::collection( $this->uniquePerms() ),
        ];
    }
}
