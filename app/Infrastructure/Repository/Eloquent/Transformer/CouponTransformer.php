<?php

namespace App\Infrastructure\Repository\Eloquent\Transformer;

use App\Domain\Model\Campaign;
use App\Domain\Model\Coupon as CouponDomain;
use App\Domain\Model\User;
use App\Infrastructure\Framework\Models\Coupon as CouponEloquent;

class CouponTransformer
{
    private $campaignTransformer;
    private $userTransformer;

    public function __construct(
        CampaignTransformer $campaignTransformer,
        UserTransformer $userTransformer
    )
    {
        $this->campaignTransformer = $campaignTransformer;
        $this->userTransformer = $userTransformer;
    }

    public function entityToDomain(CouponEloquent $entity): CouponDomain
    {
        return new CouponDomain(
            $entity->id,
            $entity->code,
            $entity->discount,
            $entity->type,
            new \DateTime($entity->start_at),
            empty($entity->end_at) ? null : new \DateTime($entity->end_at),
            empty($entity->campaign) ? null : $this->campaignTransformer->entityToDomain($entity->campaign),
            empty($entity->user) ? null : $this->userTransformer->entityToDomain($entity->user)
        );
    }

    public function domainToEntity(CouponDomain $domain): CouponEloquent
    {
        $entity = CouponEloquent::findOrNew(['id' => $domain->getId()])->first();

        $entity->code = $domain->getCode();
        $entity->discount_amount = $domain->getDiscountAmount();
        $entity->type = $domain->getType();
        $entity->start_at = $domain->getStartAt();
        $entity->end_at = $domain->getEndAt();
        $campaign = $domain->getCampaign();
        $user = $domain->getUser();
        $entity->campaign_id = empty($campaing) ? null : $campaign->getId();
        $entity->user_id = empty($user) ? null : $user->getId();

        return $entity;
    }
}
