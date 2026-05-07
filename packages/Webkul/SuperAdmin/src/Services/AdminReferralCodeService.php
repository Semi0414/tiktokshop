<?php

namespace Webkul\SuperAdmin\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Webkul\Core\Models\CoreConfig;

class AdminReferralCodeService
{
    public const CONFIG_CODE = 'general.superadmin.referral_code';

    /**
     * Return the persisted admin referral code, generating and storing one if missing.
     */
    public function ensureExists(): string
    {
        $value = CoreConfig::query()->where('code', self::CONFIG_CODE)->value('value');

        if ($value !== null && trim((string) $value) !== '') {
            return strtoupper(trim((string) $value));
        }

        return $this->generateAndStore();
    }

    /**
     * Alias for templates / callers that only need the current code.
     */
    public function get(): string
    {
        return $this->ensureExists();
    }

    protected function generateAndStore(): string
    {
        for ($attempt = 0; $attempt < 50; $attempt++) {
            $code = strtoupper(Str::random(8));

            if ($this->existsInSellerTable($code)) {
                continue;
            }

            $existing = CoreConfig::query()->where('code', self::CONFIG_CODE)->first();

            if ($existing) {
                return strtoupper(trim((string) $existing->value));
            }

            CoreConfig::query()->create([
                'code' => self::CONFIG_CODE,
                'value' => $code,
                'locale_code' => null,
                'channel_code' => null,
            ]);

            return $code;
        }

        $fallback = strtoupper(Str::random(10)).(string) random_int(10, 99);

        CoreConfig::query()->firstOrCreate(
            ['code' => self::CONFIG_CODE],
            [
                'value' => $fallback,
                'locale_code' => null,
                'channel_code' => null,
            ]
        );

        return $fallback;
    }

    protected function existsInSellerTable(string $code): bool
    {
        if (! Schema::hasTable('seller')) {
            return false;
        }

        return DB::table('seller')->where('referral_code', $code)->exists();
    }
}
