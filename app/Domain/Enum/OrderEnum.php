<?php

namespace App\Domain\Enum;

class OrderEnum extends Enum
{
    public const CREATED = 'created';
    public const PROCESSING = 'processing';
    public const PAID = 'paid';
    public const REFUSED = 'refused';

    public static function getAvailable(): array
    {
        return [
            self::CREATED,
            self::PROCESSING,
            self::PAID,
            self::REFUSED
        ];
    }

    protected function throwException(string $value): void
    {
        $available = implode(', ', static::getAvailable());

        throw new \Exception('Order type invalid '.$available);
    }
}
