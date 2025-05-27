<?php

namespace App\Http\Middleware;

use Closure;

class restaurantLogin
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
        if (! $request->session()->get('res_id') || $request->session()->get('res_role') != 2) {
            return redirect('/store')->with('error', 'Please Login to access ');
        }

        return $next($request);
    }
}
