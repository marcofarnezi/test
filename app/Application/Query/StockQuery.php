<?php

namespace App\Application\Query;

use App\Domain\Repository\StockRepository;

class StockQuery
{
    private $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function getProductStock(int $productId, int $amount): array
    {
        return $this->stockRepository->findProductStock($productId, $amount);
    }
}
