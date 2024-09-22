<?php

declare(strict_types=1);

namespace Modules\User\API\System\Restore;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\User\Domain\services\dto\RestoreDto;
use Modules\User\Domain\services\RestoreService;
use Modules\User\Exceptions\UserDeletedException;
use Modules\User\Infrastructure\RepositoryUserNotFoundException;

final readonly class Module implements Restore
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
        } catch (RepositoryUserNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (UserDeletedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
