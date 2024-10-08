<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UpdateUserRequest;
use Carbon\Carbon;
use Core\Application\User\Shared\Gateways\UserCommandInterface;
use Core\Application\User\Shared\Gateways\UserMapperInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\InvalideRules\InvalidEmailException;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Infra\Handlers\UseCases\User\Update\UpdateUserHandler;
use Ramsey\Uuid\Uuid;

class UpdateUserController extends Controller
{
    public function __construct(
        private readonly FrameworkContract $frameworkService,
        private readonly UserCommandInterface $userCommand,
        private readonly UserMapperInterface $userMapper
    ) {
    }

    /**
     * @throws InvalidEmailException
     */
    public function __invoke(UpdateUserRequest $request, string $uuid)
    {
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($uuid),
            name: $request->name,
            email: new EmailValueObject($request->email),
            password: $request->password,
            birthday: Carbon::createFromFormat('Y-m-d', $request->birthday),
            authenticableUser: $this->frameworkService->auth()->user()
        );

        try {
            $this->frameworkService
                ->transactionManager()
                ->beginTransaction();

            $output = (new UpdateUserHandler(
                userCommand: $this->userCommand,
                userMapper: $this->userMapper,
                frameworkService: $this->frameworkService
            ))->handle(input: $input);

            $this->frameworkService
                ->transactionManager()
                ->commit();
        } catch (OutputErrorException $outputErrorException) {
            $this->frameworkService
                ->transactionManager()
                ->rollBack();
            return response()->json(
                data: (new ErrorPresenter(
                    message: $outputErrorException->getMessage(),
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        }

        return response()->json(
            data: (new UserPresenter($output->userEntity))->withDataAttribute()->toArray(),
            status: ResponseStatus::OK->value
        );
    }
}
