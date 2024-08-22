<?php

namespace Core\Domain\Entities\Account;

use Core\Application\Account\Commons\Exceptions\AccountJoinCodeInvalidException;
use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeAccessors;
use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeBuilder;
use Core\Tools\Http\ResponseStatusCodeEnum;
use DateTime;
use DateTimeInterface;

class AccountJoinCodeEntity
{
    use AccountJoinCodeAccessors;
    use AccountJoinCodeBuilder;

    private ?int $id = null;
    private ?string $code = null;
    private ?int $accountId = null;
    private ?DateTimeInterface $expiresAt = null;

    private function __construct()
    {
    }

    public function validateJoinCode(): void
    {
        if ($this->expiresAt && $this->expiresAt < new DateTime()) {
            throw new AccountJoinCodeInvalidException(
                'Join code has expired',
                ResponseStatusCodeEnum::BAD_REQUEST->value
            );
        }

        if (strlen($this->code) !== 6) {
            throw new AccountJoinCodeInvalidException(
                'Join code must be 6 characters long',
                ResponseStatusCodeEnum::BAD_REQUEST->value
            );
        }
    }

}
