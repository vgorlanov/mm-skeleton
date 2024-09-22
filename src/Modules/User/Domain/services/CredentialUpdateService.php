<?php

declare(strict_types=1);

namespace Modules\User\Domain\services;

use Common\Uuid\Uuid;
use Modules\User\Domain\Credential;
use Modules\User\Domain\services\dto\CredentialDto;
use Modules\User\Domain\User;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class CredentialUpdateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function execute(Uuid $uuid, CredentialDto $data): User
    {
        $user = $this->repository->get($uuid);

        $credential = new Credential(...(array) $data);
        $user->changeCredential($credential);

        $this->repository->update($user);

        foreach ($user->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
