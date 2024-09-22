<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Modules\User\Domain\Status;
use Tests\Unit\modules\User\UserBuilder;

final class DeleteTest extends UserTest
{
    private const ROUTE = 'admin.user.delete';

    public function test_success(): void
    {
        $user = (new UserBuilder())->build();

        $this->repository->add($user);

        $this->delete($this->url(self::ROUTE, $user))->assertStatus(200);

        $user = $this->repository->get($user->getUuid());

        $this->assertSame($user->status()->current(), Status::DELETE);
    }

    public function test_validation(): void
    {
        $user = (new UserBuilder())->deleted()->build();

        $this->repository->add($user);

        $this->delete($this->url(self::ROUTE, $user))->assertStatus(422);
    }

    public function test_not_found(): void
    {
        $user = (new UserBuilder())->build();

        $this->delete($this->url(self::ROUTE, $user))->assertStatus(404);
    }
}
