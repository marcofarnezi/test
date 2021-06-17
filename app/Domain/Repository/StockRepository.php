<?php

namespace App\Domain\Repository;

use App\Domain\Model\Stock;

interface StockRepository
{
    /**
     * @param int[]|null $stocksId
     * @return Stock[]
     */
    public function getStocks(?array $stocksId): array;

    public function get(int $id): Stock;

    public function find(?int $id): ?Stock;

    public function save(Stock $stock): Stock;

    /**
     * @param int $productId
     * @param int $amount
     * @return Stock[]
     */
    public function findProductStock(int $productId, int $amount): array;

    public function new(int $productId, int $price): Stock;
}
