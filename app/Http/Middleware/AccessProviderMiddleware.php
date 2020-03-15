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
    public function handle($request, Closure $next, $requestedAccess, $requestedPage)
    {
        $accessProvider = new AccessProvider($request->user()->id, $requestedAccess);;


        //user is not confirmed yet
        if ($accessProvider->getPermission() === 'not-confirmed') {
            return $accessProvider->pendingForConfirmation();
        }

        //user is locked
        if ($accessProvider->getPermission() === 'locked') {
            return $accessProvider->userLocked();
        }
        // check permissions
        $result = $accessProvider->getPermission();
        if ($result === true) {
            return $next($request);
        } elseif ($result === false) {
            return $accessProvider->accessDenied();
        }
    }
}
