<?php

declare(strict_types=1);

namespace Modules\User\Domain\services;

use Modules\User\Domain\services\dto\BlockDto;
use Modules\User\Domain\User;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class BlockService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param BlockDto $dto
     * @return User
     * @throws \Modules\User\Exceptions\UserBlockedException
     */
    public function execute(BlockDto $dto): User
    {
        $user = $this->repository->get($dto->uuid);
        $user->block($dto->date);
        $this->repository->update($user);

        foreach ($user->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
