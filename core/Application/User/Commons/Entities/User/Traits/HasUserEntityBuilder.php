<?php

namespace Core\Application\User\Commons\Entities\User\Traits;

use Core\Application\User\Commons\Entities\User\UserEntity;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

trait HasUserEntityBuilder
{
    public static function forCreate(
        string $name,
        string $email,
        #[SensitiveParameter]
        string $password,
        UuidInterface $uuid = null,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->birthday = $birthday;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;
        $userEntity->uuid = $uuid;

        return $userEntity;
    }

    public static function record(
        int $id,
        string $name,
        string $email,
        UuidInterface $uuid,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->id = $id;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->birthday = $birthday;
        $userEntity->uuid = $uuid;

        return $userEntity;
    }

    public static function forUpdate(
        int $id,
        string $name,
        string $email,
        #[SensitiveParameter]
        string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->birthday = $birthday;
        $userEntity->id = $id;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;

        return $userEntity;
    }

    public static function forDelete(int $id): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setId($id);

        return $userEntity;
    }
}
