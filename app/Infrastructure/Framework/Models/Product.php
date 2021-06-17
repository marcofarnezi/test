<?php

namespace App\Infrastructure\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'product_id', 'id');
    }

    public function stockAvailable(): HasMany
    {
        return $this->hasMany(Stock::class, 'product_id', 'id')
            ->whereNull('sold_at');
    }
}
