<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Domain\Model\Campaign;
use App\Domain\Database\Transaction;
use App\Domain\Repository\CampaignRepository;
use App\Infrastructure\Framework\Models\Campaign as CampaignEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\CampaignTransformer;

class DBCampaignRepository implements CampaignRepository
{
    private $transaction;
    private $campaignTransformer;

    public function __construct(Transaction $transaction, CampaignTransformer $campaignTransformer)
    {
        $this->transaction = $transaction;
        $this->campaignTransformer = $campaignTransformer;
    }

    public function getCampaigns(?array $campaignsId): array
    {
        if (is_null($campaignsId)) {
            $campaigns = CampaignEntity::all();
        } else {
            $campaigns = CampaignEntity::whereIn('id', $campaignsId)->get();
        }

        return $campaigns->transform(
            function ($campaign) {
                return $this->campaignTransformer->entityToDomain($campaign);
            }
        )->toArray();
    }

    public function get(int $id): Campaign
    {
        $data = $this->find($id);
        if (is_null($data)) {
            throw new \Exception('Campaign '.$id.' not found');
        }

        return $data;
    }

    public function find(?int $id): ?Campaign
    {
        $campaign = CampaignEntity::find($id);
        if (is_null($campaign)) {
            return null;
        }

        return $this->campaignTransformer->entityToDomain($campaign);
    }

    public function save(Campaign $campaign): Campaign
    {
        $campaignEntity = $this->campaignTransformer->domainToEntity($campaign);
        $this->transaction->beginTransaction();
        try {
            $campaignEntity->save();
            $this->transaction->commit();
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
        }

        return $this->campaignTransformer->entityToDomain($campaignEntity->fresh());
    }
}
