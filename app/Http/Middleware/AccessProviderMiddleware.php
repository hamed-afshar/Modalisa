<?php

namespace App\Http\Middleware;

use App\AccessProvider;
use Closure;

class AccessProviderMiddleware
{
    /**
     * Provide access to requests based on user roles and request types.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, $requestedAccess)
    {
        $accessProvider = new AccessProvider($request->user()->id, $requestedAccess);
        if ($accessProvider->getPermission()) {
            route('all-users');
        } else {
            return $accessProvider->accessDenied();
        }
        return $next($request);
    }
}
