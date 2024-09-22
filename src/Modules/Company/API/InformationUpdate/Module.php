<?php

declare(strict_types=1);

namespace Modules\Company\API\InformationUpdate;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Company\Domain\services\dto\InformationDto;
use Modules\Company\Domain\services\InformationUpdateService;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;
use Modules\Company\Validators\InformationValidator;

final class Module implements InformationUpdate
{
    public function __construct(
        private InformationUpdateService $service,
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
            (new InformationValidator())->validate($request);

            $dto = InformationDto::make($request->all());
            $this->service->execute($uuid, $dto);
        } catch (ValidationException $e) {
            throw new ApiValidationException($e->getMessage(), $e->errors());
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        }

        return true;
    }
}
