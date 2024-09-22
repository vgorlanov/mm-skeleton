<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\EndDto;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class EndService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param EndDto $dto
     * @return Company
     * @throws \Modules\Company\Exceptions\CompanyAlreadyEndedException
     */
    public function execute(EndDto $dto): Company
    {
        $company = $this->repository->get($dto->uuid);
        $company->end($dto->date);
        $this->repository->update($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }
}
