<?php

namespace App\Site;

use Carbon\Carbon;
use App\Helpers\Models;
use Illuminate\Http\Request;

class Page extends Models
{
	///// Setup and overides

	/**
	* Casts data types
	*/
	protected $casts = [
		'content' => 'json',
		'is_active' => 'boolean'
	];

	/**
	* Add dates to be Carbon instances
	*
	* @var array
	*/
	protected $dates = ['activate_at', 'deactivate_at'];
    
    /**
    * Relationship to menu model
    */
    public function menu()
    {
    	return $this->belongsTo( Menu::class );
    }

    ///// Queries

    /**
    * get a list of pages
    *
    * @return Collection
    */
    public function fullList()
    {
    	return $this->with( 'menu' )->latest()->get();
    }

    /**
    * Activate pages that are set with an activated date
    *
    * @return boolean
    */
    public function activate()
    {
    	$now = Carbon::now();

    	// Grab pages that are needing to be activated
    	$pages = $this->where( 'activate_at', '<', $now )->where( 'is_active', false )->get();

    	if ( $pages->isNotEmpty() ) {

    		// Loop over each page
    		foreach ( $pages as $page ) {

    			// Double check deactivate is null or is in future
    			if ( is_null( $page->deactivate_at ) || $page->deactivate_at > $now) {

    				// Check and deactivate any active pages per menu item
    				$active = $this->where( 'menu_id', $page->menu_id )->where( 'is_active', true )->first();

    				if ( $active->isNotEmpty() ) {

    					$active->update( ['is_active' => false] );

    				}

    				$page->update( ['is_active' => true] );

    			}

    		}

    	}

    	return true;
    }

    /**
    * get data to display a page
    *
    * @param mixed $identifier
    * @return Model
    */
    public function show($identifier)
    {
    	$page = $this->findByIdentifier( $identifier );

    	return $page->only('content');
    }

    /**
    * Get data to edit a page
    *
    * @param mixed $identifier
    * @return Model
    */
    public function edit($identifier)
    {
    	return $this->findByIdentifier( $identifier );
    }

    /**
    * Validate user input
    *
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function store(Request $request)
    {
    	$this->validateInput( $request );

    	$page = $this->create( $this->setData($request) );

    	return $page;
    }

    /**
    * Validate user input
    *
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function renew(Request $request)
    {
    	$page = $this->findByIdentifier( $request->identifier );

    	$this->validateInput( $request, $page->id );

    	$page->update( $this->setData($request) );

    	return $page->fresh();
    }

    /**
    * Attempt to remove a page
    *
    * @param mixed $identifier
    * @return Model
    */
    public function remove($identifier)
    {
    	return $this->findByIdentifier( $identifier )->delete();
    }

    ///// Helpers

    /**
    * Set data for insert as needed
    *
    * @param \Illuminate\Http\Request $request
    * @return array
    */
    private function setData(Request $request)
    {
    	return [
    		'name' => $request->name,
    		'slug' => str_slug( $request->name ),
    		'menu_id' => $request->menu_id,
    		'content' => json_encode( $request->content ),
    		'is_active' => $request->is_active? true:false,
    		'activate_at' => $request->activate_at? Carbon::parse( $request->activate_at ):null,
    		'deactivate_at' => $request->deactivate_at? Carbon::parse( $request->deactivate_at ):null,
    	];
    }

    /**
    * Validate user input
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Request
    */
    private function validateInput(Request $request, $id = null)
    {
    	$rules = [
    		'name' => 'required|string|unique:pages,name',
    		'menu_id' => 'required|numeric|exists:menus,id',
    		'content' => 'required|string',
    		'is_active' => 'boolean',
    		'activate_at' => 'date',
    		'deactivate_at' => 'date',
    	];

    	if ( $request->isMethod('patch') ) {

    		$rules['name'] .= ','. $id;
    	}

    	return $request->validate( $rules );
    }
}
