<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Domain\Database\Transaction;
use App\Domain\Model\User as UserDomain;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Repository\Eloquent\Transformer\UserTransformer;
use App\Infrastructure\Framework\Models\User as UserEntity;

class DBUserRepository implements UserRepository
{
    /**
     * @var Transaction
     */
    private $transaction;
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    public function __construct(Transaction $transaction, UserTransformer $userTransformer)
    {
        $this->transaction = $transaction;
        $this->userTransformer = $userTransformer;
    }

    public function getUsers(?array $usersId): array
    {
        if (is_null($usersId)) {
            $users = UserEntity::all();
        } else {
            $users = UserEntity::whereIn('id', $usersId)->get();
        }

        return $users->transform(
            function ($user) {
                return $this->userTransformer->entityToDomain($user);
            }
        )->toArray();
    }

    /**
     * @throws \Exception
     */
    public function get(int $id): UserDomain
    {
        $data = $this->find($id);
        if (is_null($data)) {
            throw new \Exception('User '.$id.' not found');
        }

        return $data;
    }

    public function find(?int $id): ?UserDomain
    {
        $user = UserEntity::find($id);
        if (is_null($user)) {
            return null;
        }

        return $this->userTransformer->entityToDomain($user);
    }

    public function uniqueEmail(string $email, int $userId): bool
    {
        return UserEntity::where('email', $email)->where('id', '!=', $userId)->doesntExist();
    }

    public function save(UserDomain $user): UserDomain
    {
        $userEntity = $this->userTransformer->domainToEntity($user);
        $userEntity->save();
        return $this->userTransformer->entityToDomain($userEntity->fresh());
    }
}
