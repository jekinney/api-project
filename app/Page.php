<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
      protected $guarded = [];

      ///// Queries

       /**
       * Get a list to display to admin
       *
       * @return Collection
       */
   	public function list()
   	{
   		   return $this->get( ['id', 'position', 'location', 'name'] );
   	}

   	/**
   	* Get a page to display to users
   	*
   	* @param string $slug
   	* @return Model
   	*/
   	public function show(string $slug)
   	{
   		   return $this->where( 'slug', $slug )->first( ['name', 'content'] );
   	}

   	public function edit()
   	{
   		   return $this;
   	}

   	/**
   	* Create and insert a new page
   	*
   	* @param \Illuminate\Http\Request $request
   	* @return Model
   	*/
   	public function store(Request $request)
   	{
      		$request = $this->validation( $request );

   		   $page = $this->create( $this->setData( $request ) );

   		   return $page;
   	}

   	/**
   	* Update and insert an existing page
   	*
   	* @param \Illuminate\Http\Request $request
   	* @return Model
   	*/
   	public function renew(Request $request)
   	{
   		   $request = $this->validation( $request );

   		   $page = $this->update( $this->setData( $request ) );

   		   return $page;
   	}

   	/**
   	* Remove a page
   	*
   	* @return boolean
   	*/
   	public function remove()
   	{
   		   return $this->delete();
   	}

   	/**
   	* Update and persist page positions and locations
   	*
   	* @param \Illuminate\Http\Request $request
   	* @return Collection
   	*/
   	public function positions(Request $request)
   	{
   		   $this->resetPositions( $request );

   		   return $this->list();
   	}

   	///// Helpers

   	/**
   	* validate incoming input
   	*
   	* @param \Illuminate\Http\Request $request
   	* @return object $request
   	*/
   	private function validation(Request $request)
   	{
      		return $request->validate([
         			'name' => 'required|unique:pages,name|string',
         			'slug' => 'required|unique:pages,slug|string',
         			'content' => 'required'
         			'position' => 'required|numeric',
         			'location' => 'required|in:top,bottom|string',
      		]);
   	}

   	/**
   	* set, santize and manipulate data as needed
   	* before inserting into database
   	*
   	* @param \Illuminate\Http\Request $request
   	* @return array
   	*/
   	private function setData(Request $request)
   	{
      		return [
         			'name' => $request->name,
         			'slug' => $request->slug,
         			'content' => $request->content,
         			'position' => $request->position,
         			'location' => $request->location,
      		];
   	}
}
