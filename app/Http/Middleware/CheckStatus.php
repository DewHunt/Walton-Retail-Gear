<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckStatus
{
    public function handle($request, Closure $next)
    {
		if (Auth::user() &&  Auth::user()->status == 1) {
             return $next($request);
        }

        return redirect('/')->with('error','You have not admin access');
    }
}
