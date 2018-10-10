<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\Resource;

class CategoryResource extends Resource
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
            'name' => $this->name,
            'slug' => $this->when( $this->slug, $this->slug ),
            'description' => $this->when( $this->description, $this->description ),
            'articles_count' => $this->when( !is_null( $this->articles_count ), $this->articles_count ),

            'articles' => ArticleResource::collection( $this->whenLoaded('articles') ),
        ];
    }
}
