<?php

declare(strict_types=1);

namespace Modules\User\Domain\services;

use Common\Uuid\Uuid;
use Modules\User\Domain\Data;
use Modules\User\Domain\services\dto\DataDto;
use Modules\User\Domain\User;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class DataUpdateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(Uuid $uuid, DataDto $dto): User
    {
        $user = $this->repository->get($uuid);

        $data = new Data(...(array) $dto);
        $user->changeData($data);

        $this->repository->update($user);

        foreach ($user->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
