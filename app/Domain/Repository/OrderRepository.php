<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;
use App\Domain\Model\Order;
use App\Domain\Model\Coupon;

interface OrderRepository
{
    /**
     * @param int[]|null $ordersId
     *
     * @return Order[]
     */
    public function getOrders(?array $ordersId): array;

    public function get(int $id): Order;

    public function find(?int $id): ?Order;

    public function save(Order $order): Order;

    public function new(?User $user = null): Order;

    public function applyCoupon(Order $order, Coupon $coupon): Order;
}
