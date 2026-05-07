<?php

namespace Webkul\User\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Bouncer
{
    /**
     * Get session-create route for current guard.
     */
    protected function sessionCreateRoute(string $guard): string
    {
        return $guard.'.session.create';
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = 'admin')
    {
        $sessionCreateRoute = $this->sessionCreateRoute($guard);

        if (! auth()->guard($guard)->check()) {
            return redirect()->route($sessionCreateRoute);
        }

        $user = auth()->guard($guard)->user();

        $superAdminImpersonatingSeller = $guard === 'admin'
            && $request->session()->get('superadmin_impersonate_seller');

        /**
         * Seller panel: only active accounts (status). Super Admin impersonation may open inactive sellers.
         */
        if (! $superAdminImpersonatingSeller && ! (bool) auth()->guard($guard)->user()->status) {
            auth()->guard($guard)->logout();

            return redirect()->route($sessionCreateRoute);
        }

        /**
         * If somehow the user deleted all permissions, then it should be
         * auto logged out and need to contact the administrator again.
         */
        if (! $superAdminImpersonatingSeller && $this->isPermissionsEmpty($guard)) {
            auth()->guard($guard)->logout();

            session()->flash('error', trans('admin::app.error.403.message'));

            return redirect()->route($sessionCreateRoute);
        }

        return $next($request);
    }

    /**
     * Check for user, if they have empty permissions or not except admin.
     */
    public function isPermissionsEmpty(string $guard): bool
    {
        if (! $role = auth()->guard($guard)->user()->role) {
            abort(401, 'This action is unauthorized.');
        }

        if ($role->permission_type === 'all') {
            return false;
        }

        if (
            $role->permission_type !== 'all'
            && empty($role->permissions)
        ) {
            return true;
        }

        $this->checkIfAuthorized();

        return false;
    }

    /**
     * Check authorization.
     *
     * @return null
     */
    public function checkIfAuthorized()
    {
        $roles = acl()->getRoles();

        if (isset($roles[Route::currentRouteName()])) {
            bouncer()->allow($roles[Route::currentRouteName()]);
        }
    }
}
