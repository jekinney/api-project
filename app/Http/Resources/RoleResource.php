<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RoleResource extends Resource
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
            'id' => $this->id,
            'name' => $this->when( $this->name, $this->name ),
            'slug' => $this->when( $this->slug, $this->slug ),
            'description' => $this->when( $this->description, $this->description ),

            //'users' => new UserCollection( $this->whenLoaded('users') ),
            'permissions' => PermissionResource::collection( $this->whenLoaded('permissions') ),
        ];
    }
}
