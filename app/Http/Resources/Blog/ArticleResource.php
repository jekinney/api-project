<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\Resource;

class ArticleResource extends Resource
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
            'slug' => $this->slug,
            'body' => $this->when( $this->body , $this->body ),
            'title' => $this->title,
            'overview' => $this->when( $this->overview, $this->overview ),
            'publish_at' => $this->publish_at->format( 'm-d-Y h:m' ),

            'author' => $this->author->name,
            'category' => $this->category->name,
        ];
    }
}
