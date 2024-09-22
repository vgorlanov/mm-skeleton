<?php

declare(strict_types=1);

namespace Modules\User\Domain\services;

use Common\Uuid\Uuid;
use Modules\User\Domain\Credential;
use Modules\User\Domain\Data;
use Modules\User\Domain\services\dto\UserDto;
use Modules\User\Domain\User;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class CreateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(UserDto $dto): User
    {
        $user = new User(
            uuid: Uuid::next(),
            credential: new Credential(...(array) $dto->credential),
            data: new Data(...(array) $dto->data),
            date: new \DateTimeImmutable(),
        );

        $this->repository->add($user);

        foreach ($user->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
