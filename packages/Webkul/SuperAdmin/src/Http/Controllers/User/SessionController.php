<?php

namespace Webkul\SuperAdmin\Http\Controllers\User;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Webkul\Customer\Facades\Captcha;
use Webkul\SuperAdmin\Http\Controllers\Controller;

class SessionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        if (auth()->guard('superadmin')->check()) {
            return redirect()->route('superadmin.dashboard.index');
        }

        if (strpos(url()->previous(), 'admin') !== false) {
            $intendedUrl = url()->previous();
        } else {
            $intendedUrl = route('superadmin.dashboard.index');
        }

        session()->put('url.intended', $intendedUrl);

        return view('superadmin::users.sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->validate(request(), Captcha::getValidations([
            'email' => 'required|email',
            'password' => 'required',
        ]));

        $remember = request('remember');

        if (! auth()->guard('superadmin')->attempt(request(['email', 'password']), $remember)) {
            session()->flash('error', trans('superadmin::app.settings.users.login-error'));

            return redirect()->back();
        }

        if (! auth()->guard('superadmin')->user()->status) {
            session()->flash('warning', trans('superadmin::app.settings.users.activate-warning'));

            auth()->guard('superadmin')->logout();

            return redirect()->route('superadmin.session.create');
        }

        if (! bouncer()->hasPermission('dashboard')) {
            $allPermissions = collect(config('acl'));

            $permissions = auth()->guard('superadmin')->user()->role->permissions;

            foreach ($permissions as $permission) {
                if (bouncer()->hasPermission($permission)) {
                    $permissionDetails = $allPermissions->firstWhere('key', $permission);

                    // If key is single level (no dots), find the first child entry
                    if (! str_contains($permission, '.')) {
                        $childPermission = $allPermissions->first(function ($item) use ($permission) {
                            return str_starts_with($item['key'], $permission.'.')
                                && substr_count($item['key'], '.') === 1
                                && bouncer()->hasPermission($item['key']);
                        });

                        if ($childPermission) {
                            return redirect()->route($childPermission['route']);
                        }
                    }

                    return redirect()->route($permissionDetails['route']);
                }
            }
        }

        // Gate state: once per session.
        session()->put('captcha_gate_passed', 1);

        return redirect()->intended(route('superadmin.dashboard.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        auth()->guard('superadmin')->logout();

        return redirect()->route('superadmin.session.create');
    }
}
