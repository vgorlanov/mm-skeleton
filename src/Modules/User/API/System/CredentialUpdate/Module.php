<?php

declare(strict_types=1);

namespace Modules\User\API\System\CredentialUpdate;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\User\Domain\services\CredentialUpdateService;
use Modules\User\Domain\services\dto\CredentialDto;
use Modules\User\Infrastructure\RepositoryUserNotFoundException;
use Modules\User\Validators\CredentialValidator;

final class Module implements CredentialUpdate
{
    public function __construct(
        private CredentialUpdateService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @param Request $request
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiValidationException
     */
    public function update(Uuid $uuid, Request $request): true
    {
        try {
            (new CredentialValidator())->validate($request);

            $dto = new CredentialDto(...$request->all());

            $this->service->execute($uuid, $dto);
        } catch (ValidationException $e) {
            throw new ApiValidationException($e->getMessage(), $e->errors());
        } catch (RepositoryUserNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        }

        return true;
    }

}
