<?php

namespace App\Domain\Enum;

class CouponTypeEnum extends Enum
{
    public const VALUE = 'value';
    public const PERCENT = 'percent';

    public static function getAvailable(): array
    {
        return [
            self::VALUE,
            self::PERCENT,
        ];
    }

    protected function throwException(string $value): void
    {
        $available = implode(', ', static::getAvailable());
        throw new \Exception('Coupon type invalid '.$available);
    }
}
