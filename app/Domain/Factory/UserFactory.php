<?php

namespace App\Domain\Factory;

use App\Domain\Model\User;

class UserFactory
{
    public function getUser(
        ?int $userId = null,
        string $name,
        string $email,
        ?string $phone = null,
        ?string $address = null
    ): User
    {
        $user = new User();
        empty($userId)?:$user->setId($userId);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setAddress($address);
        return $user;
    }
}
