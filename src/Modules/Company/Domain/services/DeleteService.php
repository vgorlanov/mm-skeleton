<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\DeleteDto;
use Modules\Company\Exceptions\CompanyDeletedException;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class DeleteService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param DeleteDto $dto
     * @return Company
     * @throws CompanyDeletedException
     */
    public function execute(DeleteDto $dto): Company
    {
        $company = $this->repository->get($dto->uuid);
        $company->delete($dto->date);
        $this->repository->update($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }
}
