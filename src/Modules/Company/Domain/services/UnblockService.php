<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\UnblockDto;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class UnblockService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param UnblockDto $dto
     * @return Company
     * @throws \Modules\Company\Exceptions\CompanyBlockedException
     */
    public function execute(UnblockDto $dto): Company
    {
        $company = $this->repository->get($dto->uuid);
        $company->unblock($dto->date);
        $this->repository->update($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }
}
