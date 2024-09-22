<?php

declare(strict_types=1);

namespace Modules\User\API\System\Delete;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\User\Domain\services\DeleteService;
use Modules\User\Domain\services\dto\DeleteDto;
use Modules\User\Exceptions\UserDeletedException;
use Modules\User\Infrastructure\RepositoryUserNotFoundException;

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
        } catch (RepositoryUserNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (UserDeletedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
