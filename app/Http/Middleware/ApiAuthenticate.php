<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ApiAuthenticate extends Middleware
{

    public function handle($request, Closure $next, ...$guards)
    {
        if (!$request->bearerToken()) {
            return response()->json([], 401);
        }

        try {
            $this->authenticate($request, $guards);
        } catch (\Exception $e) {
            return response()->json([], 401);
        }

        return $next($request);
    }
}
