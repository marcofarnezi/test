<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Domain\Model\Stock;
use App\Domain\Database\Transaction;
use App\Domain\Repository\StockRepository;
use App\Infrastructure\Framework\Models\Stock as StockEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\StockTransformer;

class DBStockRepository implements StockRepository
{
    private $transaction;
    private $stockTransformer;

    public function __construct(Transaction $transaction, StockTransformer $stockTransformer)
    {
        $this->transaction = $transaction;
        $this->stockTransformer = $stockTransformer;
    }

    public function getStocks(?array $stocksId): array
    {
        if (is_null($stocksId)) {
            $stocks = StockEntity::all();
        } else {
            $stocks = StockEntity::whereIn('id', $stocksId)->get();
        }

        return $stocks->transform(
            function ($stock) {
                return $this->stockTransformer->entityToDomain($stock);
            }
        )->toArray();
    }

    public function get(int $id): Stock
    {
        $data = $this->find($id);
        if (is_null($data)) {
            throw new \Exception('Order '.$id.' not found');
        }

        return $data;
    }

    public function find(?int $id): ?Stock
    {
        $stock = StockEntity::find($id);
        if (is_null($stock)) {
            return null;
        }

        return $this->stockTransformer->entityToDomain($stock);
    }

    public function save(Stock $stock): Stock
    {
        $stockEntity = $this->stockTransformer->domainToEntity($stock);
        $this->transaction->beginTransaction();
        try {
            $stockEntity->save();
            $this->transaction->commit();
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
            throw $exception;
        }

        return $this->stockTransformer->entityToDomain($stockEntity->fresh());
    }

    public function findProductStock(int $productId, int $amount): array
    {
        return array_map(
            function (StockEntity $stock) {
                return $this->stockTransformer->entityToDomain($stock);
            },
            StockEntity::where('product_id', $productId)->take($amount)->get()
        );
    }

    public function new(int $productId, int $price): Stock
    {
        $stockEntity = new StockEntity();
        $stockEntity->product_id = $productId;
        $stockEntity->price = $price;
        $stockEntity->save();

        return $this->stockTransformer->entityToDomain($stockEntity);
    }
}
