<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireCreator
{
    /**
     * Require that a creator exist
     *
     * @param Request $request
     * @param Closure                  $next
     * @param string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!creator()) {
            return response('No Creator exists!', 401);
        }

        return $next($request);
    }
}
