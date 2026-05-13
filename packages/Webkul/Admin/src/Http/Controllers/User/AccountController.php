<?php

namespace Webkul\Admin\Http\Controllers\User;

use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function edit()
    {
        $user = auth()->guard('admin')->user();

        $sellerApplication = null;
        $applicationCountryName = null;

        if (DB::getSchemaBuilder()->hasTable('seller_applications')) {
            $sellerApplication = DB::table('seller_applications')
                ->where('seller_id', $user->id)
                ->orderByDesc('id')
                ->first();

            if ($sellerApplication && ! empty($sellerApplication->country)) {
                $match = collect(core()->countries())->firstWhere('code', $sellerApplication->country);
                $applicationCountryName = $match ? $match->name : $sellerApplication->country;
            }
        }

        return view('admin::account.edit', compact('user', 'sellerApplication', 'applicationCountryName'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        $user = auth()->guard('admin')->user();

        $this->validate(request(), [
            'name' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'current_password' => 'required|min:6',
            'image' => 'nullable|mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $data = request()->only([
            'name',
            'password',
            'password_confirmation',
            'current_password',
            'image',
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            session()->flash('warning', trans('admin::app.account.edit.invalid-password'));

            return redirect()->back();
        }

        $isPasswordChanged = false;

        if (! $data['password']) {
            unset($data['password']);
        } else {
            $isPasswordChanged = true;

            $data['password'] = bcrypt($data['password']);
        }

        if (request()->hasFile('image')) {
            $uploaded = request()->file('image');
            $data['image'] = is_array($uploaded)
                ? current($uploaded)->store('seller/'.$user->id)
                : $uploaded->store('seller/'.$user->id);
        } else {
            unset($data['image']);
        }

        unset($data['current_password'], $data['password_confirmation']);

        $user->update($data);

        if ($isPasswordChanged) {
            Event::dispatch('admin.password.update.after', $user);
        }

        session()->flash('success', trans('admin::app.account.edit.update-success'));

        return back();
    }

    /**
     * JSON: verify the signed-in admin password (for sensitive seller actions).
     */
    public function verifyPassword(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'password' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'message' => $validator->errors()->first('password'),
            ], 422);
        }

        $user = auth()->guard('admin')->user();

        if (! $user || ! Hash::check($validator->validated()['password'], $user->password)) {
            return new JsonResponse([
                'message' => trans('admin::app.account.verify-password.invalid'),
            ], 422);
        }

        return new JsonResponse(['ok' => true]);
    }
}
