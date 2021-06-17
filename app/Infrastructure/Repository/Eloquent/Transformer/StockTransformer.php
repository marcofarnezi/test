<?php

namespace App\Infrastructure\Repository\Eloquent\Transformer;

use App\Domain\Model\Stock as StockDomain;
use App\Infrastructure\Framework\Models\Stock as StockEloquent;

class StockTransformer
{
    private $productTransformer;

    public function __construct(ProductTransformer $productTransformer)
    {
        $this->productTransformer = $productTransformer;
    }

    public function entityToDomain(StockEloquent $entity): StockDomain
    {
        return new StockDomain(
            $entity->id,
            $this->productTransformer->entityToDomain($entity->product),
            $entity->price,
            empty($entity->sold_at) ? null : new \DateTime($entity->sold_at)
        );
    }

    public function domainToEntity(StockDomain $domain): StockEloquent
    {
        $entity = StockEloquent::findOrNew(['id' => $domain->getId()])->first();

        $entity->product_id = $domain->getProduct()->getId();
        $entity->price = $domain->getPrice();
        $entity->sold_at = $domain->getSoldAt()->format('Y-m-d H:i:s');

        return $entity;
    }
}
