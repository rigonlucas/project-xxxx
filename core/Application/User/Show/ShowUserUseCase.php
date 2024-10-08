<?php

namespace Core\Application\User\Show;

use Core\Application\Account\Shared\Gateways\AccountMapperInterface;
use Core\Application\User\Shared\Exceptions\UserNotFountException;
use Core\Application\User\Shared\Gateways\UserMapperInterface;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use Ramsey\Uuid\UuidInterface;

class ShowUserUseCase
{
    public function __construct(
        private readonly UserMapperInterface $userMapper,
        private readonly AccountMapperInterface $accountMapper
    ) {
    }

    /**
     * @throws UserNotFountException
     */
    public function execute(UuidInterface $uuid, UserEntity $userAuthenticaded): UserEntity
    {
        $this->validateAccessPolicies($userAuthenticaded, $uuid);
        $userEntity = $this->userMapper->findByUuid($uuid);
        if (!$userEntity) {
            throw new UserNotFountException(
                message: 'User not found',
                code: ResponseStatus::NOT_FOUND->value,
            );
        }

        $accountEntity = $this->accountMapper->findByUuid($userEntity->getAccount()->getUuid());
        $userEntity->setAccount($accountEntity);

        return $userEntity;
    }

    /**
     * @throws UserNotFountException
     */
    private function validateAccessPolicies(UserEntity $userAuthenticaded, UuidInterface $uuid): void
    {
        if (
            !$userAuthenticaded->getUuid()->equals($uuid) &&
            $userAuthenticaded->hasNotPermission(UserRoles::ADMIN)
        ) {
            throw new UserNotFountException(
                message: 'Forbidden access',
                code: ResponseStatus::FORBIDDEN->value,
            );
        }
    }
}
