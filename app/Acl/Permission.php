<?php

namespace App\Acl;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    ///// Setup and override
    
	/**
	* Guarded columns from mass assignment
	*
	* @var array
	*/
    protected $guarded = [];

    /**
    * Get all roles assigned to a permission
    */
    public function roles()
    {
    	return $this->belongsToMany( Role::class );
    }

    ///// Queries

    /**
    * Get a full list of permissions
    *
    * @return Collection
    */
    public function fullList()
    {
    	return $this->withCount( 'roles' )->orderBy( 'name', 'asc' )->get();
    }

    /**
    * Get a list of permissions for select input
    *
    * @return Collection
    */
    public function selectList()
    {
    	return $this->orderBy( 'name', 'asc' )->get(['id', 'name', 'description']);
    }

    ///// Helpers

    public function uniqueRolesCount()
    {
        $roles = $this->roles->map( function($role) {
            return $this->role->id;
        });

        dd( $roles );
    }
}
