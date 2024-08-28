<?php

namespace App\Http\Controllers\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Project\CreateProjectRequest;
use Carbon\Carbon;
use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Application\Project\Create\inputs\CreateProjectInput;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\Project\ProjectCreatedPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Infra\Handlers\UseCases\Project\Create\CreateProjectHandler;

class CreateProjectController extends Controller
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly ProjectCommandInterface $projectCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
    }

    public function __invoke(CreateProjectRequest $request)
    {
        $input = new CreateProjectInput(
            name: $request->name,
            description: $request->description,
            startAt: Carbon::createFromFormat('Y-m-d', $request->start_at),
            finishAt: Carbon::createFromFormat('Y-m-d', $request->finish_at),
            status: StatusProjectEnum::from($request->status)
        );
        try {
            $this->framework->transactionManager()->beginTransaction();

            $projectHandler = new CreateProjectHandler(
                userAuth: $this->framework->auth()->user(),
                framework: $this->framework,
                projectCommand: $this->projectCommand,
                projectMapper: $this->projectMapper
            );
            $projetoEntity = $projectHandler->handle($input);

            $this->framework->transactionManager()->commit();
        } catch (OutputErrorException $outputErrorException) {
            $this->framework->transactionManager()->rollBack();
            return response()->json(
                data: (new ErrorPresenter(
                    message: $outputErrorException->getMessage(),
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        }

        return response()->json(
            data: (new ProjectCreatedPresenter($projetoEntity))->withDataAttribute()->toArray(),
            status: ResponseStatus::CREATED->value
        );
    }
}
