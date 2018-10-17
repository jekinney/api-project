<?php

namespace App\Traits;

use Carbon\Carbon;

trait ModelHelpers
{
	/**
    * Format date for displaying
    *
    * @param \Carbon\Carbon $date
    * @return string
    */
    public function displayDate(Carbon $date)
    {
    	return $date->format( 'm-d-Y h:m A');
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