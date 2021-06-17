<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Domain\Database\Transaction;
use App\Domain\Model\Order;
use App\Domain\Model\OrderItem;
use App\Domain\Model\Stock;
use App\Domain\Repository\OrderItemRepository;
use App\Infrastructure\Framework\Models\OrderItem as OrderItemEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\OrderItemTransformer;

class DBOrderItemRepository implements OrderItemRepository
{
    private $orderItemTransformer;
    private $transaction;

    public function __construct(
        Transaction $transaction,
        OrderItemTransformer $orderItemTransformer
    )
    {
        $this->orderItemTransformer = $orderItemTransformer;
        $this->transaction = $transaction;
    }

    public function getOrderItems(?array $orderItemsId): array
    {
        if (is_null($orderItemsId)) {
            $orderItems = OrderItemEntity::all();
        } else {
            $orderItems = OrderItemEntity::whereIn('id', $orderItemsId)->get();
        }

        return $orderItems->transform(
            function ($orderItem) {
                return $this->orderItemTransformer->entityToDomain($orderItem);
            }
        )->toArray();
    }

    public function get(int $id): OrderItem
    {
        $data = $this->find($id);
        if (is_null($data)) {
            throw new \Exception('Order item '.$id.' not found');
        }

        return $data;
    }

    public function find(?int $id): ?OrderItem
    {
        $orderItem = OrderItemEntity::find($id);
        if (is_null($orderItem)) {
            return null;
        }

        return $this->orderItemTransformer->entityToDomain($orderItem);
    }

    public function save(OrderItem $orderItem): OrderItem
    {
        $orderItemEntity = $this->orderItemTransformer->domainToEntity($orderItem);
        $this->transaction->beginTransaction();
        try {
            $orderItemEntity->save();
            $this->transaction->commit();
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
        }

        return $this->orderItemTransformer->entityToDomain($orderItemEntity->fresh());
    }

    public function new(Order $order, Stock $stock): OrderItem
    {
        $orderItem = new OrderItemEntity();
        $orderItem->order_id = $order->getId();
        $orderItem->stock_id = $stock->getId();
        $orderItem->save();
        return  $this->orderItemTransformer->entityToDomain($orderItem);
    }

    public function remove(OrderItem $orderItem): bool
    {
        $orderItemEntity = $this->orderItemTransformer->domainToEntity($orderItem);

        return $orderItemEntity->delete();
    }
}
