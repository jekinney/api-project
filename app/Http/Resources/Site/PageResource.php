<?php

namespace App\Http\Resources\Site;

use Illuminate\Http\Resources\Json\Resource;

class PageResource extends Resource
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
            'slug' => $this->when( $this->slug, $this->slug ),
            'name' => $this->when( $this->name, $this->name ),
            'content' => $this->when( $this->content, $this->content ),
            'is_active' => $this->when( $this->is_active, $this->is_active ),
            'updated_at' => $this->when( $this->updated_at, $this->displayDate($this->updated_at) ),
            'created_at' => $this->when( $this->created_at, $this->displayDate($this->created_at) ),
            'activate_at' => $this->when( $this->activate_at, $this->displayDate($this->activate_at) ),
            'deactivate_at' => $this->when( $this->deactivate_at, $this->displayDate($this->deactivate_at) ),

            'menu' => new MenuResource( $this->whenLoaded('menu') ),
        ];
    }
}
