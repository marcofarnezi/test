<?php

namespace App\Infrastructure\Framework\Database;

use Illuminate\Support\Facades\DB;
use App\Domain\Database\Transaction;

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
