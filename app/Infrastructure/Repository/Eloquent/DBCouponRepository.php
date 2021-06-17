<?php

namespace App\Infrastructure\Repository\Eloquent;

use Carbon\Carbon;
use App\Domain\Model\User;
use App\Domain\Model\Coupon;
use App\Domain\Model\Campaign;
use App\Domain\Database\Transaction;
use App\Domain\Repository\CouponRepository;
use App\Infrastructure\Framework\Models\Coupon as CouponEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\CouponTransformer;

class DBCouponRepository implements CouponRepository
{
    private $transaction;
    private $couponTransformer;

    public function __construct(Transaction $transaction, CouponTransformer $couponTransformer)
    {
        $this->transaction = $transaction;
        $this->couponTransformer = $couponTransformer;
    }

    public function getCoupons(?array $couponsId): array
    {
        if (is_null($couponsId)) {
            $coupons = CouponEntity::all();
        } else {
            $coupons = CouponEntity::whereIn('id', $couponsId)->get();
        }

        return $coupons->transform(
            function ($coupon) {
                return $this->couponTransformer->entityToDomain($coupon);
            }
        )->toArray();
    }

    public function get(int $id): Coupon
    {
        $data = $this->find($id);
        if (is_null($data)) {
            throw new \Exception('Coupon '.$id.' not found');
        }

        return $data;
    }

    public function find(?int $id): ?Coupon
    {
        $coupon = CouponEntity::find($id);
        if (is_null($coupon)) {
            return null;
        }

        return $this->couponTransformer->entityToDomain($coupon);
    }

    public function save(Coupon $coupon): Coupon
    {
        $couponEntity = $this->couponTransformer->domainToEntity($coupon);
        $this->transaction->beginTransaction();
        try {
            $couponEntity->save();
            $this->transaction->commit();
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
        }

        return $this->couponTransformer->entityToDomain($couponEntity->fresh());
    }

    public function findByCode(string $code, ?int $userId = null): ?Coupon
    {
        $couponEntity = CouponEntity::where('code', $code)
            ->where('start_at', '<=', Carbon::now())
            ->where(function ($entity) {
                $entity->whereNull('end_at')
                    ->orWhere('end_at', '>=', Carbon::now());
            })
            ->whereNull('used_at')
            ->first();
        if (empty($couponEntity)) {
            throw new \Exception('Coupon cannot be applied');
        }
        if (! empty($couponEntity->user_id) && $userId !== $couponEntity->user_id) {
            return null;
        }

        return $this->couponTransformer->entityToDomain($couponEntity);
    }

    public function new(
        string $code,
        int $discount,
        string $type,
        Carbon $start,
        ?Carbon $end = null,
        ?Campaign $campaign = null,
        ?User $user = null
    ): Coupon {
        $couponEntity = new CouponEntity();
        $couponEntity->code = $code;
        $couponEntity->discount = $discount;
        $couponEntity->type = $type;
        $couponEntity->start_at = $start;
        $couponEntity->end_at = $end;
        $couponEntity->campaign_id = null === $campaign ? null : $campaign->getId();
        $couponEntity->user_id = null === $user ? null : $user->getId();

        $couponEntity->save();

        return $this->couponTransformer->entityToDomain($couponEntity);
    }
}
