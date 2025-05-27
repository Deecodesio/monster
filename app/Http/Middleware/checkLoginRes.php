<?php

namespace App\Http\Middleware;

use Closure;

class checkLoginRes
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
        if (! $request->session()->get('res_id')) {
            return redirect('/store')->with('error', 'Please Login to access ');
        }

        return $next($request);
    }
}
