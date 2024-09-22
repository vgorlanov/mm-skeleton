<?php

declare(strict_types=1);

namespace Modules\Product\API\Publish;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Product\Domain\services\dto\PublishDto;
use Modules\Product\Domain\services\PublishService;
use Modules\Product\Exceptions\ProductPublishedException;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;

final readonly class Module implements Publish
{
    public function __construct(
        private PublishService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     */
    public function publish(Uuid $uuid): true
    {
        try {
            $dto = new PublishDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryProductNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (ProductPublishedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
