<?php

namespace Webkul\User;

class Bouncer
{
    /**
     * Determine which admin-like guard is currently authenticated.
     */
    protected static function activeGuard(): string
    {
        if (auth()->guard('superadmin')->check()) {
            return 'superadmin';
        }

        if (auth()->guard('admin')->check()) {
            return 'admin';
        }

        return 'admin';
    }

    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public function hasPermission($permission)
    {
        $guard = self::activeGuard();

        if (
            auth()->guard($guard)->check()
            && auth()->guard($guard)->user()->role->permission_type == 'all'
        ) {
            return true;
        } else {
            if (
                ! auth()->guard($guard)->check()
                || ! auth()->guard($guard)->user()->hasPermission($permission)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public static function allow($permission)
    {
        $guard = self::activeGuard();

        if (
            ! auth()->guard($guard)->check()
            || ! auth()->guard($guard)->user()->hasPermission($permission)
        ) {
            abort(401, 'This action is unauthorized');
        }
    }
}
