<?php

namespace App\Infrastructure\Framework\Database;

use App\Domain\Database\Transaction;
use Illuminate\Support\Facades\DB;

class EloquentTransaction implements Transaction
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollBack(): void
    {
        DB::rollBack();
    }
}
