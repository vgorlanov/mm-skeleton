<?php

declare(strict_types=1);

namespace Modules\User\Domain\services;

use Modules\User\Domain\services\dto\UnblockDto;
use Modules\User\Domain\User;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class UnBlockService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param UnblockDto $dto
     * @return User
     * @throws \Modules\User\Exceptions\UserBlockedException
     */
    public function execute(UnblockDto $dto): User
    {
        $user = $this->repository->get($dto->uuid);
        $user->unBlock($dto->date);
        $this->repository->update($user);

        foreach ($user->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
