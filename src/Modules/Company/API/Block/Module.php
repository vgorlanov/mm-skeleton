<?php

declare(strict_types=1);

namespace Modules\Company\API\Block;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Company\Domain\services\BlockService;
use Modules\Company\Domain\services\dto\BlockDto;
use Modules\Company\Exceptions\CompanyBlockedException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

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
     */
    public function block(Uuid $uuid): true
    {
        try {
            $dto = new BlockDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (CompanyBlockedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
