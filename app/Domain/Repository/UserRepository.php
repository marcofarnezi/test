<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;

interface UserRepository
{
    /**
     * @param int[]|null $usersId
     * @return User[]
     */
    public function getUsers(?array $usersId):array;

    public function get(int $id): User;

    public function find(?int $id): ?User;

    public function uniqueEmail(string $email, int $userId): bool;

    public function save(User $user): User;
}
