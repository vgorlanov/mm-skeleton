<?php

declare(strict_types=1);

namespace Modules\Product\API\Restore;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Product\Domain\services\dto\RestoreDto;
use Modules\Product\Domain\services\RestoreService;
use Modules\Product\Exceptions\ProductDeletedException;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;

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
            $dto = new RestoreDto($uuid);
            $this->service->execute($dto);
        } catch (RepositoryProductNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (ProductDeletedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
