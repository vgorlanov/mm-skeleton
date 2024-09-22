<?php

declare(strict_types=1);

namespace Modules\User\API\System\Block;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\User\Domain\services\BlockService;
use Modules\User\Domain\services\dto\BlockDto;
use Modules\User\Exceptions\UserBlockedException;
use Modules\User\Infrastructure\RepositoryUserNotFoundException;

final readonly class Module implements Block
{
    public function __construct(
        private BlockService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     * @throws \Modules\User\Exceptions\UserBlockedException
     */
    public function block(Uuid $uuid): true
    {
        try {
            $dto = new BlockDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryUserNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (UserBlockedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
