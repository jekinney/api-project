<?php

use App\User;
use App\Acl\Role;
use App\Acl\Permission;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// ['slug' => '', 'name' => '', 'description' => ''],
        $roles = [
        	['slug' => 'site-admin', 'name' => 'Site Admin', 'description' => 'Site Admin with all permissions and manage permissions.'],
        	['slug' => 'blog-author', 'name' => 'Blog Author', 'description' => 'Can author and edit own blog articles.'],
        	['slug' => 'member', 'name' => 'Member', 'description' => 'Memeber of the site. Either no or limited permissions.'],
        ];

        $permissions = [
            ['slug' => 'access-dash', 'name' => 'Access Dashboard', 'description' => 'Access admin dashboard'],
            ['slug' => 'acl-users', 'name' => 'Acl Users', 'description' => 'Can manage users role assignments'],
            ['slug' => 'acl-roles', 'name' => 'Acl Roles', 'description' => 'Can manage roles and permissions'],
        	['slug' => 'blog-categories', 'name' => 'Blog Categories', 'description' => 'Can manage blog categories.'],
        	['slug' => 'blog-articles', 'name' => 'Blog Articles', 'description' => 'Can manage blog ALL blog articles.'],
        	['slug' => 'blog-author', 'name' => 'Blog Author', 'description' => 'Can author and update their own blog articles'],
            ['slug' => 'site-menu', 'name' => 'Site Menu', 'description' => 'Manage the sites menus'],
            ['slug' => 'site-page', 'name' => 'Site Page', 'description' => 'Manage sites static pages and content'],
        ];


        $user = User::create([
            'name' => 'Jason Kinney', 
            'email' => 'jekinneys@yahoo.com', 
            'password' => bcrypt( 'secret' ),
        ]);

        foreach ( $permissions as $permission ) {
        	Permission::create( $permission );
        }

        foreach ( $roles as $role ) {
        	Role::create( $role );
        }

        $admin = Role::where( 'slug', 'site-admin' )->first();
        $user->roles()->attach( $admin->id );
        $admin->permissions()->attach( Permission::get() );
    }
}
