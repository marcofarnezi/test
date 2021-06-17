<?php

namespace App\Infrastructure\Repository\Eloquent\Transformer;

use App\Domain\Model\Product as ProductDomain;
use App\Infrastructure\Framework\Models\Product as ProductEloquent;

class ProductTransformer
{
    public function entityToDomain(ProductEloquent $entity): ProductDomain
    {
        $productDomain = new ProductDomain(
            $entity->id,
            $entity->title,
            $entity->price,
            $entity->description
        );

        $productDomain->setCreatedAt($entity->created_at);
        $productDomain->setEditedAt($entity->edited_at);

        return $productDomain;
    }

    public function domainToEntity(ProductDomain $domain): ProductEloquent
    {
        $entity = new ProductEloquent();
        if ($domain->getId()) {
            $entity = ProductEloquent::findOrNew(['id' => $domain->getId()])->first();
        }
        $entity->title = $domain->getTitle();
        $entity->description = $domain->getDescription();
        $entity->price = $domain->getPrice();

        return $entity;
    }
}
