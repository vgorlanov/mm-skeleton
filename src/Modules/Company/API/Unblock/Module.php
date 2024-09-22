<?php

declare(strict_types=1);

namespace Modules\Company\API\Unblock;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Status\Exceptions\CurrentStatusNotExistsException;
use Common\Uuid\Uuid;
use Modules\Company\Domain\services\dto\UnblockDto;
use Modules\Company\Domain\services\UnblockService;
use Modules\Company\Exceptions\CompanyBlockedException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

final readonly class Module implements Unblock
{
    public function __construct(
        private UnblockService $service,
    ) {}

    /**
     * @throws CurrentStatusNotExistsException
     */
    public function unblock(Uuid $uuid): true
    {
        try {
            $dto = new UnblockDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (CompanyBlockedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
