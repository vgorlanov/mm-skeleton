<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Common\Uuid\Uuid;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\Contacts;
use Modules\Company\Domain\services\dto\ContactsDto;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class ContactsUpdateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(Uuid $uuid, ContactsDto $data): Company
    {
        $company = $this->repository->get($uuid);

        $company->changeContacts(new Contacts(...(array) $data));

        $this->repository->update($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }
}
