<?php

namespace App\Domain\Database;

interface Transaction
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}
