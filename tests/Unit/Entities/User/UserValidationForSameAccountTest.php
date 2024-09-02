<?php

namespace Tests\Unit\Entities\User;

use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Permissions\UserRoles;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group user_entity_validation_for_same_account
 */
class UserValidationForSameAccountTest extends TestCase
{
    public function test_success_for_users_from_different_accounts()
    {
        $this->expectException(ForbidenException::class);
        $user1 = UserEntity::forIdentify(
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountUuid: Uuid::uuid7()
        );

        $user2 = UserEntity::forIdentify(
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountUuid: Uuid::uuid7()
        );
        $user1->checkUsersAreFromSameAccount($user2);
    }

    public function test_success_for_users_from_same_account()
    {
        $this->expectNotToPerformAssertions();
        $accountUuid = Uuid::uuid7();
        $userUuid = Uuid::uuid7();
        $user1 = UserEntity::forIdentify(
            uuid: $userUuid,
            role: UserRoles::ADMIN,
            accountUuid: $accountUuid
        );

        $user2 = UserEntity::forIdentify(
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountUuid: $accountUuid
        );
        $user1->checkUsersAreFromSameAccount($user2);
    }
}
