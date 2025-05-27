<?php

namespace App\Http\Middleware;

use Closure;

class superadminLogin
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
        //dd(1);
        if (! $request->session()->get('userid') || $request->session()->get('role') != 1) {
            return redirect('/admin')->with('error', 'Please Login to access ');
        }

        return $next($request);
    }
}
