<?php

declare(strict_types=1);

namespace Modules\Company\API\ContactsUpdate;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Company\Domain\services\ContactsUpdateService;
use Modules\Company\Domain\services\dto\ContactsDto;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;
use Modules\Company\Validators\ContactsValidator;

final readonly class Module implements ContactsUpdate
{
    public function __construct(
        private ContactsUpdateService $service,
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
            (new ContactsValidator())->validate($request);

            $dto = new ContactsDto(...$request->all());
            $this->service->execute($uuid, $dto);
        } catch (ValidationException $e) {
            throw new ApiValidationException($e->getMessage(), $e->errors());
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        }


        return true;
    }
}