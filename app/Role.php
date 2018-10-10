<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	///// Set up and overrides

    protected $guarded = [];

    /**
    * get all assigned users to a role
    */
    public function users()
    {
    	return $this->belongsToMany( User::class )->withTimestamps()->select( 'id', 'name', 'email' );
    }

    /**
    * Get all permissions assigned to a role
    */
    public function permissions()
    {
    	return $this->belongsToMany( Permission::class )->select( 'id', 'slug', 'name' );
    }

    ///// Queries

    /**
    * Get a full list of roles with permissions
    *
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function fullList()
    {
    	return $this->with( 'permissions', 'users' )->orderBy( 'name', 'asc' )->get();
    }

    /**
    * Get a list of roles for a select input
    *
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function selectList()
    {
    	return $this->orderBy( 'name', 'asc' )->get(['id', 'name']);
    }

    /**
    * Get a specific role with permisisons
    *
    * @param string $identifier
    * @return Model
    */
    public function show($identifier)
    {
    	return $this->findByIdentifier( $identifier )->load( 'permissions' );
    }

    /**
    * Create a new role and attach permissions
    *
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function store(Request $request)
    {
    	// Vlaidate input
    	$this->validateInput( $request );

    	// Set data and create a new role
    	$role = $this->create( $this->setData($request) );

    	// Attach permisisons to role
    	$role->permissions()->attach( $this->setPermissions($request) );

    	// Return new role with permissions
    	return $role->load( 'permissions' );
    }

    /**
    * Update an existing role and sync permissions
    *
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function renew(Request $request)
    {
    	// Find role to be updated
    	$role = $this->findByIdentifier( $request->identifier );

    	// Validate input
    	$this->validateInput( $request, $role->id );

    	// Set data and update
    	$role->update( $this->setData($request) );

        // Remove all permissions
        $role->permissions()->detach();

        // Attach any permissions passed in
    	$role->permissions()->attach( $this->setPermissions( $request ) );

    	// Get a fresh role and permisisons
    	return $role->fresh()->load( 'permissions' );
    }

    /**
    * Detach permissions and remove a role
    *
    * @param string $ideneifier
    * @return bollean
    */
    public function remove($identifier)
    {
    	$role = $this->findByIdentifier( $identifier );

    	$role->permissions()->detach();

    	return $role->delete();
    }

    /**
    * Get a role by either id or slug
    *
    * @param string $ideneifier
    * @return Model
    */
    private function findByIdentifier($identifier)
    {
    	return $this->where( 'id', $identifier )->orWhere( 'slug', $identifier )->firstOrFail();
    }

    ///// Helpers

    /**
    * Set, manipulate and escape incoming data 
    *
    * @param /Illuminate/Http/Request $request
    * @return array
    */
    private function setData(Request $request)
    {
    	return [
    		'name' => $request->name,
    		'slug' => str_slug( $request->name ),
    		'description' => $request->description,
    	];
    }

    /**
    * Validate incoming input data
    *
    * @param /Illuminate/Http/Request $request
    * @param int $id
    * @return /Illuminate/Http/Request
    */
    private function validateInput(Request $request, $id = null)
    {
    	$rules = [
    		'name' => 'required|max:30|string|unique:roles,name',
    		'description' => 'required|max:255|string',
    		'permissions.*' => 'numeric|exists:permissions,id',
    	];

    	if ( $request->ismethod('patch') ) {

    		$rules['name'] .= ','. $id;

    	}

    	return $request->validate( $rules );
    }

    private function setPermissions(Request $request)
    {
        if ( $request->permissions ) {

            return array_unique( $request->permissions );

        }

        return null;
    }
}
