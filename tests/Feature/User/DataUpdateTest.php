<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\Unit\modules\User\UserBuilder;

final class DataUpdateTest extends UserTest
{
    protected const string ROUTE = 'user.data.update';

    public function test_success(): void
    {
        $data = $this->user->getData();

        $this->putJson($this->url(self::ROUTE, $this->user), [
            'name'       => $data->name,
            'surname'    => $data->surname,
            'patronymic' => $data->patronymic,
            'gender'     => $data->gender?->value,
            'birthday'   => $data->birthday?->format('Y-m-d H:i:s'),
        ])
            ->assertStatus(200);
    }

    public function test_user_not_found(): void
    {
        $user = (new UserBuilder())->build();

        $data = (array) $this->user->getData();
        $data['birthday'] = $data['birthday'] ? $data['birthday']->format('Y-m-d') : null;

        $this->putJson($this->url(self::ROUTE, $user), $data)
            ->assertStatus(404);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = (new UserBuilder())->build();
        $this->repository->add($this->user);
    }

}
