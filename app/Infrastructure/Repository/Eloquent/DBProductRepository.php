<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Domain\Database\Transaction;
use App\Domain\Repository\ProductRepository;
use App\Domain\Model\Product as ProductDomain;
use App\Infrastructure\Framework\Models\Product as ProductEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\ProductTransformer;

class DBProductRepository implements ProductRepository
{
    private $transaction;
    private $productTransformer;

    public function __construct(Transaction $transaction, ProductTransformer $productTransformer)
    {
        $this->transaction = $transaction;
        $this->productTransformer = $productTransformer;
    }

    public function getProducts(?array $productsId = null): array
    {
        if (is_null($productsId)) {
            $products = ProductEntity::all();
        } else {
            $products = ProductEntity::whereIn('id', $productsId)->get();
        }

        return $products->transform(
            function ($product) {
                return $this->productTransformer->entityToDomain($product);
            }
        )->toArray();
    }

    public function get(int $id): ProductDomain
    {
        $data = $this->find($id);
        if (is_null($data)) {
            throw new \Exception('Product '.$id.' not found');
        }

        return $data;
    }

    public function find(?int $id): ?ProductDomain
    {
        $product = ProductEntity::find($id);
        if (is_null($product)) {
            return null;
        }

        return $this->productTransformer->entityToDomain($product);
    }

    public function save(ProductDomain $product): ProductDomain
    {
        $productEntity = $this->productTransformer->domainToEntity($product);
        $this->transaction->beginTransaction();
        try {
            $productEntity->save();
            $this->transaction->commit();
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
        }

        return $this->productTransformer->entityToDomain($productEntity->fresh());
    }

    public function new(string $title, int $price, ?string $description): ProductDomain
    {
        $productEntity = new ProductEntity();
        $productEntity->title = $title;
        $productEntity->price = $price;
        $productEntity->description = $description;
        $productEntity->save();

        return $this->productTransformer->entityToDomain($productEntity);
    }
}
