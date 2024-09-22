<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Faker\Factory;
use Tests\Unit\modules\User\UserBuilder;

final class CredentialUpdateTest extends UserTest
{
    protected const ROUTE = 'user.credential.update';

    public function test_success(): void
    {
        $this->putJson($this->url(self::ROUTE, $this->user), [
            'email' => Factory::create()->email,
            'phone' => randomPhone(),
        ])
            ->assertStatus(200);
    }

    public function test_email_already_exist(): void
    {
        $user = (new UserBuilder())->build();
        $this->repository->add($user);

        $this->putJson($this->url(self::ROUTE, $this->user), (array) $user->getCredential())
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    public function test_user_not_found(): void
    {
        $user = (new UserBuilder())->build();

        $this->putJson($this->url(self::ROUTE, $user), (array) $user->getCredential())
            ->assertStatus(404);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = (new UserBuilder())->build();
        $this->repository->add($this->user);
    }

}
