<?php

namespace App;
use App\Http\Resources\PermissionResource;
use App\Traits\UserTokens;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, UserTokens;

    /**
     * guarded columns from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
    * Get roles assigned to a user
    */
    public function roles()
    {
    	return $this->belongsToMany( Role::class )->withTimestamps();
    }

    /**
    * Get roles assigned to a user
    */
    public function limitedRoles()
    {
        return $this->belongsToMany( Role::class )->withTimestamps()->select('id', 'slug');
    }

    /**
    * Set up a collection of permissions and set as unique
    *
    * @return Collection
    */
    public function uniquePerms()
    {
        return $this->roles->map( function($role) {
            return $role->permissions()->select('id', 'slug')->get();
        })->flatten()->unique();
    }

	/**
    * Check if a auth user has a permission
    *
    * @return boolean
    */
    public function hasPermission($perm)
    {
        return $this->uniquePerms()->contains( 'slug', $perm ); 
    }

    /**
    * Check if a auth user has a set of permissions
    *
    * @return boolean
    */
    public function hasPermissions(array $perms)
    {   
        // Default not all perms required
        $all = false;

        // check if array has all, if so remove and set all to true
        if ( in_array( 'all', $perms ) ) {

            array_pop( $perms );

            $all = true;

        }

        // Get all the user's permissions
        $uniquePerms = $this->uniquePerms();

        foreach ( $perms as $perm ) {
            // Check if user has perm
            $check = $uniquePerms->contains( 'slug', $perm );

            // if we have perm and not require all return true early
            if ( $check && !$all ) return true;

            // if no perm and required all, return
            if ( !$check && $all ) return false;
        }

        // Otherwise we have all require permissions
        return true;
    }
}
