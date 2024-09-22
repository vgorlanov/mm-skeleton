<?php

declare(strict_types=1);

namespace Modules\Company\API\Delete;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Company\Domain\services\DeleteService;
use Modules\Company\Domain\services\dto\DeleteDto;
use Modules\Company\Exceptions\CompanyDeletedException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

final class Module implements Delete
{
    public function __construct(
        private DeleteService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     */
    public function delete(Uuid $uuid): true
    {
        try {
            $dto = new DeleteDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (CompanyDeletedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
