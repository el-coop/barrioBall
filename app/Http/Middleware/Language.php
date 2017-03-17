<?php

namespace App\Http\Middleware;

use App;
use Closure;

class Language
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('appLocale') && array_key_exists($request->session()->get('appLocale'), config('languages'))) {
            App::setLocale($request->session()->get('appLocale'));
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            App::setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }
}