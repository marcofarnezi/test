<?php

namespace App\Domain\Repository;

use App\Domain\Model\Campaign;

interface CampaignRepository
{
    /**
     * @param int[]|null $campaignsId
     *
     * @return Campaign[]
     */
    public function getCampaigns(?array $campaignsId): array;

    public function get(int $id): Campaign;

    public function find(?int $id): ?Campaign;

    public function save(Campaign $campaign): Campaign;
}
