<?php

namespace App\Domain\Enum;

abstract class Enum
{
    private $value;

    public function __construct(string $value)
    {
        $this->guardValue($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    abstract public static function getAvailable(): array;

    public function equal(string $value): bool
    {
        return $this->value === $value;
    }

    abstract protected function throwException(string $value): void;

    private function guardValue(string $value): void
    {
        if (!\in_array($staticvalue, static::getAvailable(), true)) {
            $this->throwException($value);
        }
    }
}
