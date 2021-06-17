<?php

namespace App\Infrastructure\Framework\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Domain\Enum\CampaignTypeEnum;
use App\Domain\Repository\CampaignRepository;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateNewCampaign extends Command
{
    protected $signature = 'make:campaign {name} {type} {start} {end}';
    protected $description = 'Create a campaign';

    private $campaignRepository;

    public function __construct(
        CampaignRepository $campaignRepository
    ) {
        $this->campaignRepository = $campaignRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        if (empty($name)) {
            $this->line('Name problem: make:campaign {string:name} {string:type} {dateTime:start} {dateTime:end}');
        }

        $type = $this->argument('type');
        $availableTypes = CampaignTypeEnum::getAvailable();
        if (! in_array($type, $availableTypes)) {
            $this->line('Type problem: types available - ', implode(', ', $availableTypes));
        }

        $start = $this->argument('start');
        $end = $this->argument('end');
        $startDate = empty($start) ? Carbon::now() : Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $endDate = empty($end) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $end);

        $campaign = $this->campaignRepository->new(
            $name,
            $type,
            $startDate,
            $endDate
        );
        $this->line('Success: campaign@'.$campaign->getId());

        return CommandAlias::SUCCESS;
    }
}
