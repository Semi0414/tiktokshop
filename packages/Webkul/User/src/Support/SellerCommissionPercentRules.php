<?php

namespace Webkul\User\Support;

/**
 * Commission % UI and validation by seller_level (store label as on seller row).
 */
class SellerCommissionPercentRules
{
    public const LEVELS = [
        'Beginner',
        'C',
        'B',
        'A',
        'S',
        'SS',
        'SSS',
    ];

    /**
     * @return array{min: float, max: float, default: float, readonly: bool}
     */
    public static function forLevel(?string $level): array
    {
        $level = self::normalizeLevel($level);

        return match ($level) {
            'Beginner' => ['min' => 15.0, 'max' => 15.0, 'default' => 15.0, 'readonly' => true],
            'C' => ['min' => 15.0, 'max' => 18.0, 'default' => 15.0, 'readonly' => false],
            'B' => ['min' => 18.0, 'max' => 21.0, 'default' => 18.0, 'readonly' => false],
            'A' => ['min' => 21.0, 'max' => 25.0, 'default' => 21.0, 'readonly' => false],
            'S' => ['min' => 25.0, 'max' => 30.0, 'default' => 25.0, 'readonly' => false],
            'SS' => ['min' => 30.0, 'max' => 35.0, 'default' => 30.0, 'readonly' => false],
            'SSS' => ['min' => 35.0, 'max' => 45.0, 'default' => 35.0, 'readonly' => false],
            default => ['min' => 15.0, 'max' => 15.0, 'default' => 15.0, 'readonly' => true],
        };
    }

    public static function normalizeLevel(?string $level): string
    {
        $level = trim((string) $level);

        if ($level === '') {
            return 'Beginner';
        }

        return in_array($level, self::LEVELS, true) ? $level : 'Beginner';
    }

    public static function isPercentAllowed(?string $level, float $percent): bool
    {
        $rule = self::forLevel($level);

        return $percent >= $rule['min'] - 0.0001 && $percent <= $rule['max'] + 0.0001;
    }
}
