<?php

namespace Core\Application\User\Show;

use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Http\ResponseStatusCodeEnum;

class ShowUserUseCase
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AccountRepositoryInterface $accountRepository
    ) {
    }

    /**
     * @throws UserNotFountException
     */
    public function execute(string $uuid): UserEntity
    {
        $userEntity = $this->userRepository->findByUuid($uuid);
        if (!$userEntity) {
            throw new UserNotFountException(
                message: 'User not found',
                code: ResponseStatusCodeEnum::NOT_FOUND->value,
            );
        }

        $accountEntity = $this->accountRepository->findByid($userEntity->getAccount()->getId());
        $userEntity->setAccount($accountEntity);

        return $userEntity;
    }
}
