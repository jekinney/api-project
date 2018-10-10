<?php

namespace App\Http\Middleware;

use Closure;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        if ( $request->user()->hasPermissions( $permissions ) ) {

            return $next($request);

        }

        abort( 501, 'not authorized' );
    }
}
