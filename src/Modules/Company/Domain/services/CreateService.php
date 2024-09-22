<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\Company\Domain\About;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\Contacts;
use Modules\Company\Domain\Information;
use Modules\Company\Domain\services\dto\CompanyDto;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class CreateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(CompanyDto $dto): Company
    {
        $company = new Company(
            uuid: Uuid::next(),
            about: new About(...(array) $dto->about),
            contacts: new Contacts(...(array) $dto->contacts),
            information: new Information(...(array) $dto->information),
            date: new DateTimeImmutable(),
        );

        $this->repository->add($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }
}
