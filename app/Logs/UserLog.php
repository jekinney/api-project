<?php

namespace App\Logs;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
	///// Setup and overrides
	
	protected $guarded = [];


	///// Relationships

	public function user()
	{
		return $thisd->belongsTo( \App\User::class );
	}

	public function usersloggable()
	{
		return $this->morphTo();
	}
}