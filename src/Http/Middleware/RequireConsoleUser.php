<?php

namespace Aprog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireConsoleUser
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user(), 401, 'Authentication required.');

        return $next($request);
    }
}
