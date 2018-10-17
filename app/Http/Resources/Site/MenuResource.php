<?php

namespace App\Http\Resources\Site;

use Illuminate\Http\Resources\Json\Resource;

class MenuResource extends Resource
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
            'position' => $this->when( $this->position, $this->position ),
            'location' => $this->when( $this->location, $this->location ),
            'pages_count' => $this->when( !is_null($this->pages_count), $this->pages_count ),

            'pages' => PageResource::collection( $this->whenLoaded('pages') ),
        ];
    }
}
