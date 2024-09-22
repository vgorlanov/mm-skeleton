<?php

declare(strict_types=1);

namespace Modules\User\Domain\services;

use Modules\User\Domain\services\dto\ActivateDto;
use Modules\User\Domain\User;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class ActivateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param ActivateDto $dto
     * @return User
     * @throws \Modules\User\Exceptions\UserAlreadyActivatedException
     */
    public function execute(ActivateDto $dto): User
    {
        $user = $this->repository->get($dto->uuid);
        $user->activate($dto->date);
        $this->repository->update($user);

        foreach ($user->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
