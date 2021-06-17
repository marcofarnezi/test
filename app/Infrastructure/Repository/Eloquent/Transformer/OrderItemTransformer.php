<?php

namespace App\Infrastructure\Repository\Eloquent\Transformer;

use App\Domain\Model\OrderItem as OrderItemDomain;
use App\Infrastructure\Framework\Models\OrderItem as OrderItemEloquent;

class OrderItemTransformer
{
    private $orderTransformer;
    private $stockTransformer;
    private $couponTransformer;

    public function __construct(
        OrderTransformer $orderTransformer,
        StockTransformer $stockTransformer,
        CouponTransformer $couponTransformer
    )
    {
        $this->orderTransformer = $orderTransformer;
        $this->stockTransformer = $stockTransformer;
        $this->couponTransformer = $couponTransformer;
    }

    public function entityToDomain(OrderItemEloquent $entity): OrderItemDomain
    {
        return new OrderItemDomain(
            $entity->id,
            $this->orderTransformer->entityToDomain($entity->order),
            $this->stockTransformer->entityToDomain($entity->stock),
            empty($entity->coupon) ? null : $this->couponTransformer->entityToDomain($entity->coupon),
        );
    }

    public function domainToEntity(OrderItemDomain $domain): OrderItemEloquent
    {
        $entity = OrderItemEloquent::findOrNew(['id' => $domain->getId()])->first();

        $coupon = $domain->getCoupon();
        $entity->order_id = $domain->getOrder()->getId();
        $entity->stock_id = $domain->getStock()->getId();
        $entity->coupon_id = empty($coupon) ? null : $coupon->getId();

        return $entity;
    }
}
