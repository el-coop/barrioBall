<?php

namespace App\Http\Middleware\Users;

use App\Models\Admin;
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
    	if(! $request->user() || ! $request->user()->can('admin', Admin::class)){
			return redirect(action('HomeController@index'));
		}
        return $next($request);
    }
}
