<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Webkul\Core\Models\CoreConfig;
use Webkul\SuperAdmin\Services\AdminReferralCodeService;
use Webkul\User\Services\SellerJoinSellerService;

class LandingController extends Controller
{
    public function index()
    {
        return view('shop::landing.index');
    }

    public function joinForm()
    {
        return view('shop::landing.join');
    }

    public function submitJoin(Request $request): RedirectResponse
    {
        if (class_exists(AdminReferralCodeService::class)) {
            app(AdminReferralCodeService::class)->ensureExists();
        }

        $request->merge([
            'email' => $request->filled('email') ? trim((string) $request->input('email')) : null,
            'invite_code' => strtoupper(trim((string) $request->input('invite_code', ''))),
        ]);

        $data = $request->validate([
            'shop_logo' => 'required|image|max:4096',
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string|max:500',
            'country' => ['required', 'string', Rule::exists('countries', 'code')],
            'id_passport_number' => 'required|string|max:120',
            'legal_name' => 'required|string|max:255',
            'document_front' => 'required|image|max:4096',
            'document_back' => 'required|image|max:4096',
            'document_selfie' => 'required|image|max:4096',
            'verify_type' => 'required|in:email,mobile',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::requiredIf(fn () => $request->input('verify_type') === 'email'),
                Rule::unique('seller', 'email'),
            ],
            'mobile' => 'nullable|string|max:50|required_if:verify_type,mobile',
            'password' => 'required|string|min:6|confirmed',
            'invite_code' => [
                'required',
                'string',
                'max:120',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $code = strtoupper(trim((string) $value));

                    $adminCode = strtoupper(trim((string) CoreConfig::query()
                        ->where('code', 'general.superadmin.referral_code')
                        ->value('value')));

                    if ($adminCode !== '' && $code === $adminCode) {
                        return;
                    }

                    if (! DB::table('seller')->where('referral_code', $code)->exists()) {
                        $fail(__('The selected invite code is invalid.'));
                    }
                },
            ],
            'agreement' => 'accepted',
        ]);

        try {
            $sellerId = DB::transaction(function () use ($request, $data) {
                $sellerId = app(SellerJoinSellerService::class)->createFromJoinForm([
                    'shop_name' => $data['shop_name'],
                    'password' => $data['password'],
                    'email' => $data['email'] ?? null,
                    'mobile' => $data['mobile'] ?? null,
                ]);

                $baseDir = 'seller-applications/'.$sellerId;

                $paths = [
                    'shop_logo' => $request->file('shop_logo')->store($baseDir, 'public'),
                    'document_front' => $request->file('document_front')->store($baseDir, 'public'),
                    'document_back' => $request->file('document_back')->store($baseDir, 'public'),
                    'document_selfie' => $request->file('document_selfie')->store($baseDir, 'public'),
                ];

                DB::table('seller_applications')->insert([
                    'seller_id' => $sellerId,
                    'shop_logo' => $paths['shop_logo'],
                    'shop_name' => $data['shop_name'],
                    'shop_address' => $data['shop_address'],
                    'country' => $data['country'],
                    'id_passport_number' => $data['id_passport_number'],
                    'legal_name' => $data['legal_name'],
                    'document_front' => $paths['document_front'],
                    'document_back' => $paths['document_back'],
                    'document_selfie' => $paths['document_selfie'],
                    'verify_type' => $data['verify_type'],
                    'email' => $data['email'] ?? null,
                    'mobile' => $data['mobile'] ?? null,
                    'password' => null,
                    'invite_code' => $data['invite_code'],
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $sellerId;
            });
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('shop.landing.join')
                ->withInput()
                ->withErrors(['shop_name' => 'Registration could not be completed. Please try again.']);
        }

        return redirect()->route('shop.landing.join')
            ->with('success', 'Your seller account has been created. You can sign in once an administrator activates your account.');
    }
}
