<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Domain\Model\User;
use App\Domain\Model\Order;
use App\Domain\Model\Coupon;
use App\Domain\Enum\OrderEnum;
use App\Domain\Enum\CouponTypeEnum;
use App\Domain\Repository\OrderRepository;
use App\Infrastructure\Framework\Models\Order as OrderEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\OrderTransformer;

class DBOrderRepository implements OrderRepository
{
    private $orderTransformer;

    public function __construct(OrderTransformer $orderTransformer)
    {
        $this->orderTransformer = $orderTransformer;
    }

    public function getOrders(?array $ordersId): array
    {
        if (is_null($ordersId)) {
            $orders = OrderEntity::all();
        } else {
            $orders = OrderEntity::whereIn('id', $ordersId)->get();
        }

        return $orders->transform(
            function ($order) {
                return $this->orderTransformer->entityToDomain($order);
            }
        )->toArray();
    }

    public function get(int $id): Order
    {
        $data = $this->find($id);
        if (is_null($data)) {
            throw new \Exception('Order '.$id.' not found');
        }

        return $data;
    }

    public function find(?int $id): ?Order
    {
        $order = OrderEntity::find($id);
        if (is_null($order)) {
            return null;
        }

        return $this->orderTransformer->entityToDomain($order);
    }

    public function save(Order $order): Order
    {
        $orderEntity = $this->orderTransformer->domainToEntity($order);
        $orderEntity->save();

        return $this->orderTransformer->entityToDomain($orderEntity);
    }

    public function new(?User $user = null): Order
    {
        $orderEntity = new OrderEntity();
        $orderEntity->total = 0;
        $orderEntity->discount = 0;
        $orderEntity->status = OrderEnum::CREATED;
        $orderEntity->user_id = empty($user) ?: $user->getId();
        $orderEntity->save();

        return $this->orderTransformer->entityToDomain($orderEntity);
    }

    public function applyCoupon(Order $order, Coupon $coupon): Order
    {
        $total = $order->getTotal();
        $type = $coupon->getType();
        $discount = $coupon->getDiscountAmount();
        if (CouponTypeEnum::PERCENT === $type) {
            $discount = round($total * $discount / 100);
        }
        $order->setCoupon($coupon);
        $order->setDiscount((int) $discount);

        return $this->save($order);
    }
}
