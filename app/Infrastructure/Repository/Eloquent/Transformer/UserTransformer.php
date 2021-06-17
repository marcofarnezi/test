<?php

namespace App\Infrastructure\Repository\Eloquent\Transformer;

use App\Domain\Model\User as UserDomain;
use App\Infrastructure\Framework\Models\User as UserEloquent;

class UserTransformer
{
    public function entityToDomain(UserEloquent $entity): UserDomain
    {
        $userDomain = new UserDomain();
        $userDomain->setId($entity->id);
        $userDomain->setName($entity->name);
        $userDomain->setEmail($entity->email);

        $userDomain->setPhone($entity->phone);
        $userDomain->setAddress($entity->address);

        return $userDomain;
    }

    public function domainToEntity(UserDomain $domain): UserEloquent
    {
        $entity = new UserEloquent();
        if ($domain->getId()) {
            $entity = UserEloquent::findOrNew(['id' => $domain->getId()])->first();
            if (empty($entity)) {
                throw new \Exception('User not found');
            }
        }

        $entity->name = $domain->getName();
        $entity->email = $domain->getEmail();
        $entity->phone = $domain->getPhone();
        $entity->address = $domain->getAddress();

        return $entity;
    }
}
