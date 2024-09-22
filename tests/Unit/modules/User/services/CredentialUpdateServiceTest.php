<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\CredentialUpdateService;
use Modules\User\Domain\services\dto\CredentialDto;
use Tests\Unit\modules\User\UserBuilder;

final class CredentialUpdateServiceTest extends UserService
{
    public function test_success(): void
    {
        $service = new CredentialUpdateService($this->repository, $this->dispatcher);

        $new = (new UserBuilder())->build();

        $service->execute($this->user->getUuid(), new CredentialDto(...(array) $new->getCredential()));

        $user = $this->repository->get($this->user->getUuid());

        $this->assertEquals($user->getCredential(), $new->getCredential());
    }
}
