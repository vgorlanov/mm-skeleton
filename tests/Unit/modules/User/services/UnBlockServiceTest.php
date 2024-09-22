<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\dto\UnblockDto;
use Modules\User\Domain\services\UnBlockService;
use Modules\User\Domain\Status;
use Tests\Unit\modules\User\UserBuilder;

final class UnBlockServiceTest extends UserService
{
    public function test_success(): void
    {
        $user = (new UserBuilder())->blocked()->build();
        $this->repository->add($user);

        $service = new UnBlockService($this->repository, $this->dispatcher);

        $dto = new UnblockDto($user->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $user = $this->repository->get($dto->uuid);

        $this->assertSame($user->status()->current(), Status::NEW);
    }
}
