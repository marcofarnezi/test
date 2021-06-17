<?php

namespace App\Infrastructure\Repository\Eloquent\Transformer;

use App\Domain\Model\Campaign as CampaignDomain;
use App\Infrastructure\Framework\Models\Campaign as CampaignEloquent;

class CampaignTransformer
{
    public function entityToDomain(CampaignEloquent $entity): CampaignDomain
    {
        return new CampaignDomain(
            $entity->id,
            $entity->name,
            new \DateTime($entity->start_at),
            new \DateTime($entity->end_at)
        );
    }

    public function domainToEntity(CampaignDomain $domain): CampaignEloquent
    {
        $entity = CampaignEloquent::findOrNew(['id' => $domain->getId()]);

        $entity->name = $domain->getName();
        $entity->start_at = $domain->getStartAt();
        $entity->end_at = $domain->getEndAt();

        return $entity;
    }
}
