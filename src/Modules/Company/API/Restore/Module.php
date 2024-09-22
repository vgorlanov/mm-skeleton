<?php

declare(strict_types=1);

namespace Modules\Company\API\Restore;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Company\Domain\services\dto\RestoreDto;
use Modules\Company\Domain\services\RestoreService;
use Modules\Company\Exceptions\CompanyDeletedException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

final class Module implements Restore
{
    public function __construct(
        private RestoreService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     */
    public function restore(Uuid $uuid): true
    {
        try {
            $dto = new RestoreDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (CompanyDeletedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
