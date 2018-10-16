<?php

namespace App\Logs\Traits;

use App\Logs\UserLog;

trait UserLogs
{
	 /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::updating( function($model) {
        	$model->createLog();
        });
    }

	///// Relatiopnships

	/**
	* Get all related logs
	*/
	public function logs()
	{
		return $this->morphMany( UserLog::class, 'userloggable' );
	}

	///// Helpers

	/**
	* Create a newlog record
	*/
	public function createLog()
	{
		if ( $this->isDirty() ) {

			$this->logs()->create([
				'after' => $this->getOriginal()->asJson(),
				'before' => $this->getDirty()->asJson(),
				'user_id' => auth()->id(),
			]);

		}

	}
}