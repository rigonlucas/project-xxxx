<?php

namespace Core\Application\User\Commons\Gateways;

use Core\Domain\Collections\User\UserCollection;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;

interface UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity;

    public function findByUuid(string $uuid): ?UserEntity;

    public function findByEmail(string $email): ?UserEntity;

    public function existsEmail(string $email): bool;

    public function existsId(int $id): bool;

    public function accountUserList(AccountEntity $account): UserCollection;
}
