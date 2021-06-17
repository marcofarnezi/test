<?php

namespace App\Domain\Repository;

use Carbon\Carbon;
use App\Domain\Model\User;
use App\Domain\Model\Coupon;
use App\Domain\Model\Campaign;

interface CouponRepository
{
    /**
     * @param int[]|null $couponsId
     *
     * @return Coupon[]
     */
    public function getCoupons(?array $couponsId): array;

    public function get(int $id): Coupon;

    public function find(?int $id): ?Coupon;

    public function save(Coupon $coupon): Coupon;

    public function findByCode(string $code, ?int $userId = null): ?Coupon;

    public function new(
        string $code,
        int $discount,
        string $type,
        Carbon $start,
        ?Carbon $end = null,
        ?Campaign $campaign = null,
        ?User $user = null
    ): Coupon;
}
