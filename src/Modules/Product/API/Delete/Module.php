<?php

declare(strict_types=1);

namespace Modules\Product\API\Delete;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Product\Domain\services\DeleteService;
use Modules\Product\Domain\services\dto\DeleteDto;
use Modules\Product\Exceptions\ProductDeletedException;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;

final readonly class Module implements Delete
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
        } catch (RepositoryProductNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (ProductDeletedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
