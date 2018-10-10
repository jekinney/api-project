<?php

use App\User;
use App\Role;
use App\Permission;
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
        	['slug' => 'blog-categories', 'name' => 'Blog Categories', 'description' => 'Can manage blog categories.'],
        	['slug' => 'blog-articles', 'name' => 'Blog Articles', 'description' => 'Can manage blog ALL blog articles.'],
        	['slug' => 'blog-author', 'name' => 'Blog Author', 'description' => 'Can author and update their own blog articles'],
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
        $admin->permissions()->attach( Permission::whereIn( 'slug', ['blog-categories', 'blog-articles', 'blog-author'] )->get() );
    }
}
