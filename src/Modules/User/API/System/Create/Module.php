<?php

declare(strict_types=1);

namespace Modules\User\API\System\Create;

use Illuminate\Http\Request;
use Modules\User\Domain\services\CreateService;
use Modules\User\Domain\services\dto\CredentialDto;
use Modules\User\Domain\services\dto\DataDto;
use Modules\User\Domain\services\dto\UserDto;
use Modules\User\Validators\CredentialValidator;
use Modules\User\Validators\UserValidator;

final readonly class Module implements Create
{
    public function __construct(
        private CreateService $service,
    ) {}

    /**
     * @param Request $request
     * @return true
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): true
    {
        (new UserValidator(
            new CredentialValidator(),
        ))->validate($request);

        $dto = new UserDto(
            credential: new CredentialDto(...$request->get('credential')),
            data: DataDto::make($request->get('data')),
        );

        $this->service->execute($dto);

        return true;
    }
}
