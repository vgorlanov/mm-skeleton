<?php

declare(strict_types=1);

namespace Modules\User\Domain\services;

use Modules\User\Domain\services\dto\DeleteDto;
use Modules\User\Domain\User;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class DeleteService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param DeleteDto $dto
     * @return User
     * @throws \Modules\User\Exceptions\UserDeletedException
     */
    public function execute(DeleteDto $dto): User
    {
        $user = $this->repository->get($dto->uuid);
        $user->delete($dto->date);
        $this->repository->update($user);

        foreach ($user->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
