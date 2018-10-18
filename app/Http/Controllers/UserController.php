<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\User\{
    UserResource,
    UserCollection
};

class UserController extends Controller
{
    /**
    * @var object
    */
    protected $user;

    /**
    * Ensure new User instace 
    */
    function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fullList()
    {
        return new UserCollection( $this->user->fullList() );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectList()
    {
        return new UserCollection( $this->user->selectList() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new UserResource( $this->user->store($request) );
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function show($identifier)
    {
        return new UserResource( $this->user->show() );
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
        $request->merge(['identifier' => $identifier]);

        return new UserResource( $this->user->renew($request) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function destroy($identifier)
    {
        return response()->json( $this->user->remove($identifier) );
    }
}
