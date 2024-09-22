<?php

declare(strict_types=1);

namespace Modules\Company\API\Activate;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Company\Domain\services\ActivateService;
use Modules\Company\Domain\services\dto\ActivateDto;
use Modules\Company\Exceptions\CompanyAlreadyActivatedException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

final readonly class Module implements Activate
{
    public function __construct(
        private ActivateService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiResponseException
     * @throws ApiNotFoundException
     */
    public function activate(Uuid $uuid): true
    {
        try {
            $dto = new ActivateDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (CompanyAlreadyActivatedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
