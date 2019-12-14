<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
        if($request->user() && $request->user()->type != "a"){
            abort(403);
        }
        if($request->user() && $request->user()->type == "a"){
            return $next($request);
        }

        return redirect()->route('login');
    }
}
