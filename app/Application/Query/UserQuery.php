<?php

namespace App\Application\Query;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

class UserQuery
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getDetail(int $userId): User
    {
        return $this->userRepository->get($userId);
    }

}
