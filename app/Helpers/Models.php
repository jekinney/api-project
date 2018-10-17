<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class Models extends Model
{
	protected $guarded = [];

	/**
    * Format date for displaying
    *
    * @param \Carbon\Carbon $date
    * @return string
    */
    public function displayDate($date)
    {
        // Check if we have a date and is an carbon instance
        if ( is_null($date) || !is_a($date, Carbon::class, true) ) {

            return null;

        }

    	return $date->format( 'm-d-Y h:m A' );
    }

    /**
    * Find a row by either id or slug
    *
    * @param mixed $identifier
    * @return Model
    */
    protected function findByIdentifier($identifier)
    {
    	return $this->where( 'id', $identifier )->orWhere( 'slug', $identifier )->firstOrFail();
    }
}