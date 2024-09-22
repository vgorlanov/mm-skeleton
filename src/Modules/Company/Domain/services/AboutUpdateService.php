<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services;

use Common\Uuid\Uuid;
use Modules\Company\Domain\About;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\AboutDto;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class AboutUpdateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(Uuid $uuid, AboutDto $dto): Company
    {
        $company = $this->repository->get($uuid);

        $about = new About(...(array) $dto);
        $company->changeAbout($about);

        $this->repository->update($company);

        foreach ($company->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $company;
    }
}
