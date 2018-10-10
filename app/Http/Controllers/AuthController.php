<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\UserTokenResource;

class AuthController extends Controller
{
	protected $user;

	function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
     * Get the authenticated User
     *
     * @return json user object
     */
	public function user(Request $request)
    {
    	return new AuthUserResource( $request->user() );
    }

    /**
     * Login user and create token
     *
     * @param  string email
     * @param  string password
     * @param  boolean remember_me
     * @return string access_token
     * @return string token_type
     * @return string expires_at
     */
    public function login(Request $request)
    {
    	$result = $this->user->attemptLogin( $request );

    	if ( isset( $result->type ) ) {

    		return ( new MessageResource( $result ) )->response()->setStatusCode( 422 );

    	}

    	return new UserTokenResource( $result );
    }

     /**
     * Logout user (Revoke the token)
     *
     * @return string message
     */
    public function logout(Request $request)
    {
    	$result = $this->user->revoke( $request );

    	return new MessageResource( $result );
    }

    /**
     * Create user
     *
     * @param  string name
     * @param  string email
     * @param  string password
     * @param  string password_confirmation
     * @return string message
     */
    public function signup(Request $request)
    {
    	$result = $this->user->register( $request );

        // return response
        return ( new MessageResource( $result ) )->response()->setStatusCode( 201 );
    }
}
