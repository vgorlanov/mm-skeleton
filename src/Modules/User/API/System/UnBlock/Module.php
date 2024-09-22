<?php

declare(strict_types=1);

namespace Modules\User\API\System\UnBlock;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\User\Domain\services\dto\UnblockDto;
use Modules\User\Domain\services\UnBlockService;
use Modules\User\Exceptions\UserBlockedException;
use Modules\User\Infrastructure\RepositoryUserNotFoundException;

final readonly class Module implements UnBlock
{
    public function __construct(
        private UnBlockService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     */
    public function unblock(Uuid $uuid): true
    {
        try {
            $dto = new UnblockDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryUserNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (UserBlockedException  $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
