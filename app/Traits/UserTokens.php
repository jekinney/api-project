<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait UserTokens
{
	public function attemptLogin(Request $request)
	{
		$request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ( ! auth()->attempt( request(['email', 'password']) ) ) {

            return (object) ['type' =>'error', 'message' => 'Invalid password'];

        }

        $user = $request->user()->load( 'roles' );

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ( $request->remember_me ) {

            $token->expires_at = Carbon::now()->addWeeks(1);

        }

        $token->save();

        return (object) [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];
	}

	public function revoke(Request $request)
	{
		$request->user()->token()->revoke();

		return (object) ['type' => 'success', 'message' => 'User\'s token has been revoked'];
	}

	public function register(Request $request)
	{
		// validate incoming input
    	$request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

    	// create a new user
        $this->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt( $request->password ),
        ]);

        return (object) ['type' => 'success', 'message' => 'Successfully created user!'];
	}
}