<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\ActivateDto;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class ActivateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param ActivateDto $dto
     * @return Company
     * @throws \Modules\Company\Exceptions\CompanyAlreadyActivatedException
     */
    public function execute(ActivateDto $dto): Company
    {
        $company = $this->repository->get($dto->uuid);
        $company->activate($dto->date);
        $this->repository->update($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }

}
