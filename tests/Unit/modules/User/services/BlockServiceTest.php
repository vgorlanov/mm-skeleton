<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\dto\BlockDto;
use Modules\User\Domain\services\BlockService;
use Modules\User\Domain\Status;

final class BlockServiceTest extends UserService
{
    public function test_success(): void
    {
        $service = new BlockService($this->repository, $this->dispatcher);

        $dto = new BlockDto($this->user->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $user = $this->repository->get($dto->uuid);

        $this->assertSame($user->status()->current(), Status::BLOCK);
    }
}
