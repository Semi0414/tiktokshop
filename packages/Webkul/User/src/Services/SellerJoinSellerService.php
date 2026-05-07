<?php

namespace Webkul\User\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Creates `seller` rows for join-form applications (email or mobile) and backfills from `seller_applications`.
 */
class SellerJoinSellerService
{
    /**
     * Create a seller account from the public join form payload (plain password).
     *
     * @param  array{shop_name: string, password: string, email?: ?string, mobile?: ?string}  $data
     */
    public function createFromJoinForm(array $data): int
    {
        $email = $this->resolveUniqueSellerEmail($data['email'] ?? null, $data['mobile'] ?? null);

        return DB::table('seller')->insertGetId([
            'name' => $data['shop_name'],
            'email' => $email,
            'password' => bcrypt($data['password']),
            'api_token' => Str::random(80),
            'role_id' => (int) config('seller.join_role_id', 1),
            'status' => 0,
            'seller_approval_status' => 'pending',
            'max_visible_products' => 0,
            'referral_code' => $this->generateUniqueReferralCode(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function generateUniqueReferralCode(): string
    {
        for ($i = 0; $i < 50; $i++) {
            $code = strtoupper(Str::random(8));
            if (! DB::table('seller')->where('referral_code', $code)->exists()) {
                return $code;
            }
        }

        return strtoupper(Str::random(10)).(string) random_int(10, 99);
    }

    /**
     * Return existing seller id when application email matches; otherwise insert a new seller row.
     */
    public function ensureSellerForApplication(object $application): int
    {
        $email = $application->email !== null ? trim((string) $application->email) : '';

        if ($email !== '') {
            $existingId = DB::table('seller')->where('email', $email)->value('id');

            if ($existingId) {
                return (int) $existingId;
            }
        }

        return $this->createFromApplicationRow($application);
    }

    /**
     * Create a seller from an existing `seller_applications` row (password already hashed).
     */
    public function createFromApplicationRow(object $application): int
    {
        $email = $this->resolveUniqueSellerEmail($application->email ?? null, $application->mobile ?? null);

        return DB::table('seller')->insertGetId([
            'name' => $application->shop_name,
            'email' => $email,
            'password' => $application->password,
            'api_token' => Str::random(80),
            'role_id' => (int) config('seller.join_role_id', 1),
            'status' => 0,
            'seller_approval_status' => 'pending',
            'max_visible_products' => 0,
            'referral_code' => $this->generateUniqueReferralCode(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Use real email when present; otherwise a unique placeholder (mobile-based or random).
     */
    public function resolveUniqueSellerEmail(?string $email, ?string $mobile): string
    {
        $email = $email !== null ? trim($email) : '';

        if ($email !== '') {
            return $email;
        }

        return $this->makeUniquePlaceholderEmail($mobile);
    }

    public function makeUniquePlaceholderEmail(?string $mobile): string
    {
        $digits = preg_replace('/\D/', '', (string) $mobile);
        $base = $digits !== '' ? 'sms-'.$digits : 'join-'.Str::lower(Str::random(12));

        for ($i = 0; $i < 200; $i++) {
            $candidate = $base.($i > 0 ? '-'.$i : '').'@join.local';
            if (! DB::table('seller')->where('email', $candidate)->exists()) {
                return $candidate;
            }
        }

        return $base.'-'.Str::lower(Str::random(16)).'@join.local';
    }
}
