<?php

namespace App\Domain\Enum;

class CampaignTypeEnum extends Enum
{
    public const FIRST_PURCHASE = 'first_purchase';

    public static function getAvailable(): array
    {
        return [
            self::FIRST_PURCHASE,
        ];
    }

    protected function throwException(string $value): void
    {
        $available = implode(', ', static::getAvailable());
        throw new \Exception('Campaign type invalid '.$available);
    }
}
