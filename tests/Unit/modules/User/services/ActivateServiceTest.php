<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\services\dto\ActivateDto;
use Modules\User\Domain\services\ActivateService;
use Modules\User\Domain\Status;

final class ActivateServiceTest extends UserService
{
    public function test_success(): void
    {
        $service = new ActivateService($this->repository, $this->dispatcher);

        $dto = new ActivateDto($this->user->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $user = $this->repository->get($dto->uuid);

        $this->assertSame($user->status()->current(), Status::ACTIVE);
    }

}
