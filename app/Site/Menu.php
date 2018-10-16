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
    public function getPageAttribute()
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
    * Position count
    *
    * @return int
    */
    public function positionCount($location)
    {
        return $this->where( 'location', $location )->count() + 1;
    }

 	/**
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return \Illuminate\Http\Request
 	*/
 	public function show($identifier)
 	{
        return $this->findByIdentifier( $identifier );
 	}

 	/**
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return \Illuminate\Http\Request
 	*/
 	public function store(Request $request)
 	{
        $this->validateInput( $request );

        $menu = $this->create( $this->setData($request) );

        return $menu;
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

 		return $menu->fresh();
 	}

    /**
    * Set position from a drag and drop json array
    *
    * @param array | json $postion
    */
    public function updatePositions($positions)
    {
        // check if NOT an array, assume json then
        if ( ! is_array($positions) ) {

            $positions = json_decode( $positions, true );

        }

        foreach ( $positions as $position ) {

            $menu = $this->findOrFail( $position['id'] );

            $menu->update([
                'position' => $position['position']
            ]);

        }

        return true;
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
            'name' => $request->name,
            'slug' => str_slug( $request->name ),
            'location' => $request->location,
            'position' => $request->position,
 		];
 	}

 	/**
 	* Validate incoming input data
 	*
 	* @param \Illuminate\Http\Request $request
 	* @return \Illuminate\Http\Request
 	*/
 	private function validateInput(Request $request, $id = null)
 	{
 		$rules = [
            'name' => 'required|string|unique:menus,name',
            'location' => 'required|string|in:top,left,right,bottom',
            'position' => 'required|numeric',
 		];

 		if ( $request->isMethod('patch') && $id ) {

            $rules['name'] .= ','. $id;

 		} 

 		return $request->validate( $rules );
 	}
}
