<?php

namespace Usoft\Ufit\Middlewares;

use Closure;
use Illuminate\Http\Request;

class TimezoneMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($timezone=$request->header('Timezone')) {
            date_default_timezone_set($timezone);
        }
        return $next($request);
    }
}
