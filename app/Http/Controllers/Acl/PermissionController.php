<?php

namespace App\Http\Controllers\Acl;

use App\Acl\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Acl\PermissionCollection;

class PermissionController extends Controller
{
    /**
    * @var \App\Permission
    */
    protected $permission;

    /**
    * Constructor
    */
    function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\PermissionCollection
     */
    public function full()
    {
        return new PermissionCollection( $this->permission->fullList() );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\PermissionCollection
     */
    public function select()
    {
        return new PermissionCollection( $this->permission->selectList() );
    }
}
