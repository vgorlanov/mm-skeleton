<?php

declare(strict_types=1);

namespace Modules\Company\API\End;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Company\Domain\services\dto\EndDto;
use Modules\Company\Domain\services\EndService;
use Modules\Company\Exceptions\CompanyAlreadyEndedException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

final class Module implements End
{
    public function __construct(
        private EndService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     */
    public function end(Uuid $uuid): true
    {
        try {
            $dto = new EndDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryCompanyNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (CompanyAlreadyEndedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
