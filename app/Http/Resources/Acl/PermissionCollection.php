<?php

namespace App\Http\Resources\Acl;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'permisisons' => PermissionResource::collection( $this->collection ),
        ];
    }
}
