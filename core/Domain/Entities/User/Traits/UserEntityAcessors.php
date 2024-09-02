<?php

namespace Core\Domain\Entities\User\Traits;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\InvalideRules\InvalidEmailException;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

trait UserEntityAcessors
{
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws InvalidEmailException
     */
    public function setEmail(EmailValueObject $email): self
    {
        $this->email = new EmailValueObject($email, false);
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(
        #[SensitiveParameter]
        string $password
    ): self {
        $this->password = $password;
        return $this;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(?UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getAccount(): ?AccountEntity
    {
        return $this->account;
    }

    public function setAccount(?AccountEntity $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function setUserOwner(bool $userOwner): self
    {
        $this->userOwner = $userOwner;
        return $this;
    }

    public function isUserOwner(): bool
    {
        return $this->userOwner;
    }
}