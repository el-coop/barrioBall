<?php

namespace App\Http\Middleware\Users;

use Closure;

class OnlyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if(!$request->user() || $request->user()->user_type !== 'Admin'){
			return redirect(action('HomeController@index'));
		}
        return $next($request);
    }
}
