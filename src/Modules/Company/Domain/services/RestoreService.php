<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\RestoreDto;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class RestoreService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param RestoreDto $dto
     * @return Company
     * @throws \Modules\Company\Exceptions\CompanyDeletedException
     */
    public function execute(RestoreDto $dto): Company
    {
        $company = $this->repository->get($dto->uuid);
        $company->restore($dto->date);
        $this->repository->update($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }

}
