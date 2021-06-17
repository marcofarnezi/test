<?php

namespace App\Domain\Repository;

use App\Domain\Model\Product;

interface ProductRepository
{
    /**
     * @param int[]|null $productsId
     * @return Product[]
     */
    public function getProducts(?array $productsId = null): array;

    public function get(int $id): Product;

    public function find(?int $id): ?Product;

    public function save(Product $product): Product;

    public function new(string $title, int $price, ?string $description): Product;
}
