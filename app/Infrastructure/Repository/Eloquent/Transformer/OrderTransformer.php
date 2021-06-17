<?php

namespace App\Infrastructure\Repository\Eloquent\Transformer;

use App\Domain\Model\Order as OrderDomain;
use App\Infrastructure\Framework\Models\Order as OrderEloquent;

class OrderTransformer
{
    private $userTransformer;
    private $couponTransformer;

    public function __construct(
        UserTransformer $userTransformer,
        CouponTransformer $couponTransformer
    )
    {
        $this->userTransformer = $userTransformer;
        $this->couponTransformer = $couponTransformer;
    }

    public function entityToDomain(OrderEloquent $entity): OrderDomain
    {
        return new OrderDomain(
            $entity->id,
            $entity->total,
            $entity->status,
            $entity->discount,
            empty($entity->user) ? null : $this->userTransformer->entityToDomain($entity->user),
            empty($entity->coupon) ? null : $this->couponTransformer->entityToDomain($entity->coupon)
        );
    }

    public function domainToEntity(OrderDomain $domain): OrderEloquent
    {
        $entity = new OrderEloquent();
        if ($domain->getId()) {
            $entity = OrderEloquent::findOrNew(['id' => $domain->getId()])->first();
        }
        $entity->total = $domain->getTotal();
        $entity->discount = $domain->getDiscount();
        $user = $domain->getUser();
        $coupon = $domain->getCoupon();
        $entity->status = $domain->getStatus();
        $entity->user_id = empty($user) ? null : $user->getId();
        $entity->coupon_id = empty($coupon) ? null : $coupon->getId();
        return $entity;
    }
}
