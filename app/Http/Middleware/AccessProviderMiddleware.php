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
        if ($accessProvider->getPermission()) {
            switch ($requestedPage) {
                case 'all-users' :
                    route('all-users');
                    break;
                case 'show-user':
                    route('show-user');
                    break;
                case 'user-edit-form':
                    route('user-edit-form');
                    break;
                case 'user-profile':
                    route('user-profile');
                    break;
                case 'user-delete':
                    route('user-delete');
                    break;
                case 'all-roles':
                    route('all-roles');
                    break;
                case 'role-create-form':
                    route('role-create-form');
                    break;
                case 'save-role':
                    route('save-role');
                    break;
                case 'show-role':
                    route('show-role');
                    break;
                case'role-edit-form':
                    route('role-edit-form');
                    break;
                case 'update-role':
                    route('update-role');
                    break;
                case 'delete-role':
                    route('delete-role');
                    break;
                default:
            }

        } else {
            return $accessProvider->accessDenied();
        }
        return $next($request);
    }
}
