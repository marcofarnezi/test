<?php

namespace App\Infrastructure\Framework\Console\Commands;

use App\Application\Query\CampaignQuery;
use App\Application\Query\OrderQuery;
use App\Domain\Enum\CouponTypeEnum;
use App\Domain\Repository\CouponRepository;
use App\Domain\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CheckCouponCampaign extends Command
{
    protected $signature = 'coupon:campaign';
    protected $description = 'send coupons by campaign';

    private $orderQuery;
    private $campaignQuery;
    private $couponRepository;
    private $userRepository;

    public function __construct(
        OrderQuery $orderQuery,
        CampaignQuery $campaignQuery,
        CouponRepository $couponRepository,
        UserRepository $userRepository
    )
    {
        $this->orderQuery = $orderQuery;
        $this->campaignQuery = $campaignQuery;
        $this->couponRepository = $couponRepository;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        if ($campaign = $this->campaignQuery->hasCampaignSendCouponFirstPurchase()) {
            $users = $this->orderQuery->getUsersFirstPurchase();
            foreach ($users as $userId) {
                $this->couponRepository->new(
                    str_random(10),
                    500,
                    CouponTypeEnum::getAvailable(),
                    Carbon::now(),
                    null,
                    $campaign,
                    $this->userRepository->get($userId)
                );
            }
            $this->line('Success: users@'. implode(', ', $users));

        }
        return CommandAlias::SUCCESS;
    }
}
