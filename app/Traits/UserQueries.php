<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait UserQueries
{
	/**
	* Full list of users
	* 
	* @param \Illuminate\Http\Request $request
	* @return Collection
	*/
	public function fullList()
	{
		return $this->with( 'roles' )->orderBy( 'name', 'desc' )->paginate( 20 );
	}

	/**
	* Minimal list of users
	* 
	* @param \Illuminate\Http\Request $request
	* @return Collection
	*/
	public function selectList()
	{
		return $this->orderBy( 'name', 'desc' )->get( ['id', 'name'] );
	}

	/**
	* Get data to show a user
	* 
	* @param \Illuminate\Http\Request $request
	* @return Model
	*/
	public function show($idendifier)
	{
		return $this->findByIdentifer( $idendifier )->load( 'roles' );
	}

	/**
	* Create a new user
	* 
	* @param \Illuminate\Http\Request $request
	* @return Model
	*/
	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|unique:users,name',
			'email' => 'required|email',
			'password' => 'required|confirmed|between:6,16|string',
		]);

		return $this->create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt( $request->password ),
		]);
	}

	/**
	* Update a user
	* 
	* @param \Illuminate\Http\Request $request
	* @return Model
	*/
	public function renew(Request $request)
	{
		$user = $this->findByIdentifer( $request->idendifier );

		$request->validate([
			'name' => 'required|string|unique:users,name,'. $user->id,
			'email' => 'required|email',
		]);

		$user->update([
			'name' => $request->name,
			'email' => $request->email,
		]);

		return $user->fresh();
	}

	/**
	* Update a user's password
	* 
	* @param \Illuminate\Http\Request $request
	* @return Model
	*/
	public function forceNewPassword(Request $request)
	{
		$user = $this->findByIdentifer( $request->idendifier );

		$request->validate([
			'password' => 'required|confirmed|between:6,16|string',
		]);

		$user->update([
			'password' => bcrypt( $request->password ),
		]);

		return $user->fresh();
	}

	/**
	* Update the auth user's email address
	* 
	* @param \Illuminate\Http\Request $request
	* @return Model
	*/
	public function renewEmail(Request $request)
	{
		$request = $request->validate([
			'email' => 'required|email|unique:users,email',
		]);

		$this->update([
			'email' => $request->email
		]);

		return $this->fresh();
	}

	/**
	* Update the auth user's name
	* 
	* @param \Illuminate\Http\Request $request
	* @return Model
	*/
	public function renewName(Request $request)
	{
		$request = $request->validate([
			'name' => 'required|string|max:70|unique:users,name',
		]);

		$this->update([
			'name' => $request->name
		]);

		return $this->fresh();
	}

	/**
	* Update the auth user's password
	* 
	* @param \Illuminate\Http\Request $request
	* @return Model
	*/
	public function renewPassword(Request $request)
	{
		$password = $this->password;

		$request = $request->validate([
			'current' => [
				'required',  
				function ( $attribute, $value, $fail ) use ( $password ) {

            		if ( !password_verify( $value, $password) ) {

                		$fail( 'Current password is not correct.' );

            		}
            	},
			],
			'password' => 'required|between:6,16|string|confirmed',
		]);

		$this->update([
			'password' => bcrypt( $request->password )
		]);

		return $this->fresh();
	}

	public function remove($idendifier)
	{
		return $this->findByIdentifer( $idendifier )->delete();
	}

}