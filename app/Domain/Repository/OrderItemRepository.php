<?php

namespace App\Domain\Repository;

use App\Domain\Model\Order;
use App\Domain\Model\OrderItem;
use App\Domain\Model\Stock;

interface OrderItemRepository
{
    /**
     * @param int[]|null $orderItemsId
     * @return OrderItem[]
     */
    public function getOrderItems(?array $orderItemsId): array;

    public function get(int $id): OrderItem;

    public function find(?int $id): ?OrderItem;

    public function save(OrderItem $orderItem): OrderItem;

    public function new(Order $order, Stock $stock): OrderItem;

    public function remove(OrderItem $orderItem): bool;
}
