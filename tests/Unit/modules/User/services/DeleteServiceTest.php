<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\dto\DeleteDto;
use Modules\User\Domain\services\DeleteService;
use Modules\User\Domain\Status;

final class DeleteServiceTest extends UserService
{
    public function test_success(): void
    {
        $service = new DeleteService($this->repository, $this->dispatcher);

        $dto = new  DeleteDto($this->user->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $user = $this->repository->get($dto->uuid);

        $this->assertSame($user->status()->current(), Status::DELETE);
    }

}
