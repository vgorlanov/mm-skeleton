<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\DataUpdateService;
use Modules\User\Domain\services\dto\DataDto;
use Tests\Unit\modules\User\UserBuilder;

final class DataUpdateServiceTest extends UserService
{
    public function test_success(): void
    {
        $service = new  DataUpdateService($this->repository, $this->dispatcher);

        $new = (new UserBuilder())->build();

        $service->execute($this->user->getUuid(), new DataDto(...(array) $new->getData()));

        $user = $this->repository->get($this->user->getUuid());

        $this->assertEquals($user->getData(), $new->getData());
    }
}
