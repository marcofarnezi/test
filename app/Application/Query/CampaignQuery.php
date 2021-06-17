<?php

namespace App\Application\Query;

use Carbon\Carbon;
use App\Domain\Enum\CampaignTypeEnum;
use App\Infrastructure\Framework\Models\Campaign as CampaignEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\CampaignTransformer;

class CampaignQuery
{
    private $transformer;

    public function __construct(CampaignTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function hasCampaignSendCouponFirstPurchase(): ?Campaign
    {
        return CampaignEntity::where('type', CampaignTypeEnum::FIRST_PURCHASE)
            ->where('start_at', '<', Carbon::now())
            ->where('end_at', '>', Carbon::now())
            ->first();
    }
}
