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
            switch ($requestedPage) {
                case 'users.index' :
                    route('users.index');
                    break;
                case 'users.show':
                    route('users.show');
                    break;
                case 'users.edit':
                    route('users.edit');
                    break;
                case 'users.update':
                    route('users.update');
                    break;
                case 'users.destroy':
                    route('users.destroy');
                    break;
                case 'roles.index':
                    route('roles.index');
                    break;
                case 'roles.create':
                    route('roles.create');
                    break;
                case 'roles.store':
                    route('roles.store');
                    break;
                case 'roles.show':
                    route('roles.show');
                    break;
                case'roles.edit':
                    route('roles.edit');
                    break;
                case 'roles.update':
                    route('roles.update');
                    break;
                case 'roles.destroy':
                    route('roles.destroy');
                    break;
                case 'see-permissions':
                    route('permissions.index');
                    break;
                case 'permissions.create':
                    route('permissions.create');
                    break;
                case 'permissions.store':
                    route('permissions.store');
                    break;
                case 'permissions.show':
                    route('permissions.show');
                    break;
                case 'permissions.edit':
                    route('permissions.edit');
                    break;
                case 'permissions.update':
                    route('permissions.update');
                    break;
                case 'permissions.destroy':
                    route('permissions.destroy');
                    break;
            }

        } elseif ($result === false) {
            return $accessProvider->accessDenied();
        }
        return $next($request);
    }
}
