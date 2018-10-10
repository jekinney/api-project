<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

use App\Http\Resources\{
    RoleResource,
    RoleCollection
};

class RoleController extends Controller
{
    /**
    * Role model
    */ 
    protected $role;

    /**
    * Constructor:
    * Always start with a new Role instance
    */
    function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function full()
    {
        return new RoleCollection( $this->role->fullList() );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function select()
    {
        return new RoleCollection( $this->role->selectList() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new RoleResource( $this->role->store($request) );
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function show($identifier)
    {
        return new RoleResource( $this->role->show($identifier) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $identifier)
    {
        $request->merge( compact('identifier') );

        return new RoleResource( $this->role->renew($request) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function destroy($identifier)
    {
        $this->role->remove( $identifier );

        return response()->json( ['success' => 'Role has been removed'], 201 );
    }
}
