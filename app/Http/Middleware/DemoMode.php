<?php

namespace App\Http\Middleware;

use Closure;

class DemoMode
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
        // dd(env('DEMO_ENV'));
        if (env('DEMO_ENV')) {
            if ($request->isMethod('post')) {
                return redirect()->back()->withErrors(env('DEMO_ERR_MSG', 'This action is not allowed.'))->withInput();
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }
    }
}
