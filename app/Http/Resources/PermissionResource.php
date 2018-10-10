<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PermissionResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd( $this->uniqueRolesCount() );

        return [
            'id' => $this->id,
            'slug' => $this->when( $this->slug, $this->slug ),
            'name' => $this->when( $this->name, $this->name ),
            'description' => $this->when( $this->description, $this->description ),
            'roles_count' => $this->when( $this->roles_count, $this->roles_count ),
        ];
    }
}
