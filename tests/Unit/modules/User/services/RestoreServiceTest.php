<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\dto\RestoreDto;
use Modules\User\Domain\services\RestoreService;
use Modules\User\Domain\Status;
use Tests\Unit\modules\User\UserBuilder;

final class RestoreServiceTest extends UserService
{
    public function test_success(): void
    {
        $service = new RestoreService($this->repository, $this->dispatcher);

        $dto = new RestoreDto($this->user->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $user = $this->repository->get($dto->uuid);

        $this->assertSame($user->status()->current(), Status::NEW);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = (new UserBuilder())->deleted()->build();

        $this->repository->add($this->user);
    }

}
