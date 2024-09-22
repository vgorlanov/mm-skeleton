<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\Unit\modules\User\UserBuilder;

final class CreateTest extends UserTest
{
    private const ROUTE = 'user.create';

    /**
     * @var array<mixed>
     */
    private array $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = (new UserBuilder())->build();
        $data = (array) $this->user->getData();
        $data['birthday'] = $data['birthday'] ? $data['birthday']->format('Y-m-d') : null;

        $this->request = [
            'credential' => (array) $this->user->getCredential(),
            'data'       => $data,
        ];
    }

    public function test_success(): void
    {
        $this->postJson($this->url(self::ROUTE), $this->request)
            ->assertStatus(201);
    }

    public function test_email_empty_validation(): void
    {
        $this->postJson($this->url(self::ROUTE), [
            'credential' => [
                'phone' => random_int(11111111111, 99999999999),
            ],
            'data'       => $this->request['data'],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'credential.email',
            ]);
    }

    public function test_email_format_validation(): void
    {
        $this->postJson($this->url(self::ROUTE), [
            'credential' => [
                'email' => 'invalid_format',
                'phone' => random_int(11111111111, 99999999999),
            ],
            'data'       => $this->request['data'],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'credential.email',
            ]);
    }


    public function test_email_already_exists(): void
    {
        $this->repository->add($this->user);

        $this->postJson($this->url(self::ROUTE), $this->request)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'credential.email',
            ]);
    }
}
