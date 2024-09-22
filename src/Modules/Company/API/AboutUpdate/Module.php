<?php

declare(strict_types=1);

namespace Modules\Company\API\AboutUpdate;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Company\Domain\services\AboutUpdateService;
use Modules\Company\Domain\services\dto\AboutDto;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;
use Modules\Company\Validators\AboutValidator;

final readonly class Module implements AboutUpdate
{
    public function __construct(
        private AboutUpdateService $service,
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
            (new AboutValidator())->validate($request);

            $dto = new AboutDto(...$request->all());
            $this->service->execute($uuid, $dto);
        } catch (ValidationException $e) {
            throw new ApiValidationException($e->getMessage(), $e->errors());
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        }

        return true;
    }
}
