<?php

declare(strict_types=1);

namespace Modules\Product\API\UnPublish;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Product\Domain\services\dto\UnPublishDto;
use Modules\Product\Domain\services\UnPublishService;
use Modules\Product\Exceptions\ProductPublishedException;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;

final readonly class Module implements UnPublish
{
    public function __construct(
        private UnPublishService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     */
    public function unPublish(Uuid $uuid): true
    {
        try {
            $dto = new UnPublishDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryProductNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (ProductPublishedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
