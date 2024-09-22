<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Modules\User\Domain\Status;
use Tests\Unit\modules\User\UserBuilder;

final class BlockTest extends UserTest
{
    private const ROUTE = 'admin.user.block';

    public function test_success(): void
    {
        $user = (new UserBuilder())->build();

        $this->repository->add($user);

        $this->patch($this->url(self::ROUTE, $user))->assertStatus(200);

        $user = $this->repository->get($user->getUuid());

        $this->assertSame($user->status()->current(), Status::BLOCK);
    }

    public function test_validation(): void
    {
        $user = (new UserBuilder())->blocked()->build();

        $this->repository->add($user);

        $this->patch($this->url(self::ROUTE, $user))->assertStatus(422);
    }

    public function test_not_found(): void
    {
        $user = (new UserBuilder())->build();

        $this->patch($this->url(self::ROUTE, $user))->assertStatus(404);
    }
}
