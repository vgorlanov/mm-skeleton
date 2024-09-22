<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\CreateService;
use Modules\User\Domain\services\dto\CredentialDto;
use Modules\User\Domain\services\dto\DataDto;
use Modules\User\Domain\services\dto\UserDto;
use Tests\Unit\modules\User\UserBuilder;

final class CreateServiceTest extends UserService
{
    private UserDto $userCreate;

    public function test_success(): void
    {
        $service = new CreateService($this->repository, $this->dispatcher);

        $user = $service->execute($this->userCreate);

        $this->assertEquals($this->repository->get($user->getUuid()), $user);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $user = (new UserBuilder())->build();

        $this->userCreate = new UserDto(
            credential: new CredentialDto(...(array) $user->getCredential()),
            data: new DataDto(...(array) $user->getData()),
        );

    }


}
