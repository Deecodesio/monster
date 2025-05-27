<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Config;

class Locale
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
        $raw_locale = $request->session()->get('locale') ?? Config::get('app.locale');
        App::setLocale($raw_locale);

        return $next($request);
    }
}
