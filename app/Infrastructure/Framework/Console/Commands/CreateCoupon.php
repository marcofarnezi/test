<?php

namespace App\Infrastructure\Framework\Console\Commands;

use App\Domain\Enum\CouponTypeEnum;
use App\Domain\Repository\CampaignRepository;
use App\Domain\Repository\CouponRepository;
use App\Domain\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateCoupon extends Command
{
    protected $signature = 'make:coupon {code} {discount} {type} {--start=} {--end=} {--campaign=} {--user=}';
    protected $description = 'Create a coupon';

    private $campaignRepository;
    private $userRepository;
    private $couponRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        UserRepository $userRepository,
        CouponRepository $couponRepository
    )
    {
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
        $this->couponRepository = $couponRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $code = $this->argument('code');
        if (empty($code)) {
            $this->line('Code problem: make:coupon {string:code} {float:discount} {string:type}
            {dateTime|null:start} {dateTime|null:end} {int|null:campaign} {int|null:user}');
            return CommandAlias::FAILURE;
        }

        $discount = $this->argument('discount');
        if (empty($code) || is_float($discount)) {
            $this->line('Discount problem: make:coupon {string:code} {float:discount} {string:type}
            {dateTime|null:start} {dateTime|null:end} {int|null:campaign} {int|null:user}');
            return CommandAlias::FAILURE;
        }

        $type = $this->argument('type');
        $availableTypes = CouponTypeEnum::getAvailable();
        if (!in_array($type, $availableTypes)) {
            $this->line('Type problem: types available - ', implode(', ', $availableTypes));
            return CommandAlias::FAILURE;
        }

        $start = $this->option('start');
        $end = $this->option('end');
        $startDate = empty($start) ? Carbon::now() : Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $endDate = empty($end) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $end);

        $campaignId = $this->option('campaign');
        $campaign = $this->campaignRepository->find($campaignId);
        if (!empty($campaignId) && empty($campaign)){
            $this->line('Campaign problem: Campaign does not exit.');
            return CommandAlias::FAILURE;
        }

        $userId = $this->option('user');
        $user = $this->userRepository->find($userId);
        if (!empty($userId) && empty($user)) {
            $this->line('User problem: User does not exit.');
        }

        $coupon = $this->couponRepository->new(
            $code,
            $discount,
            $type,
            $startDate,
            $endDate,
            $campaign,
            $user
        );
        $this->line('Success: coupon@'.$coupon->getId());
        return CommandAlias::SUCCESS;
    }
}
