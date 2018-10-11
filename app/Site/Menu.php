<?php

namespace App\Site;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    /**
    * Get pages assigned to a menu item
    */
    public function pages()
    {
    	return $this->hasMany( Page::class );
    }

    /**
    * Get attribute for an active page
    *
    * @return \App\Site\Page 
    */
    public function getActivePageAttribute()
    {
    	return $this->pages()->where( 'is_active', 1 )->first();
    }

 	///// Query

    /**
 	* Get a full list for admin
 	*
 	* @return Collection
 	*/
 	public function fullList()
 	{
 		return $this->withCount( 'pages' )->orderBy( 'position', 'asc' )->get();
 	}

 	/**
 	*
 	* @return Collection
 	*/
 	public function menuList()
 	{
 		return $this->groupBy( 'location' )->get( ['id', 'name'] );
 	}

 	/**
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return \Illuminate\Http\Request
 	*/
 	public function selectList()
 	{
 		return $this->get( ['id', 'name', 'location'] );
 	}

 	/**
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return \Illuminate\Http\Request
 	*/
 	public function show($identifier)
 	{

 	}

 	/**
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return \Illuminate\Http\Request
 	*/
 	public function store(Request $request)
 	{

 	}

 	/**
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return Model
 	*/
 	public function renew(Request $request)
 	{
 		$menu = $this->findByIdentifier( $request->identifier );

 		$this->validateInput( $request, $menu->id  );

 		$menu->update( $this->setData($request) );

 		return $menu->fresh()->load( 'roles' );
 	}

 	/**
 	* Perminatly remove menu item
 	*
 	* @return boolean
 	*/
 	public function remove()
 	{
 		foreach( $this->pages as $page ) {
 			$page->update( ['menu_id' => null] );
 		}

 		return $this->delete();
 	}

 	/**
 	*
 	* @param string $identifier
 	* @return Model
 	*/
 	private function findByIdentifier($identifier)
 	{
 		return $this->where( 'id', $identifier )
 				->orWhere( 'slug', $identifier )
 				->firstOrFail();
 	}

 	///// Helpers

 	/**
 	* Set data for inserting into database
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return array
 	*/
 	private function setData(Request $request)
 	{
 		return [

 		];
 	}

 	/**
 	* Validate incoming input data
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return \Illuminate\Http\Request
 	*/
 	private function validateInput(Request $request)
 	{
 		$rules = [

 		];

 		if ( $request->isMethod('post') ) {



 		} else {



 		}

 		return $request->validate( $rules );
 	}
}
