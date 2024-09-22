<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\User\Domain\Credential;
use Modules\User\Domain\Data;
use Modules\User\Domain\Gender;
use Modules\User\Domain\User;

final class UserBuilder
{
    private Uuid $uuid;

    private Data $data;

    private Credential $credential;

    private \DateTimeImmutable $date;

    private bool $block = false;

    private bool $active = false;

    private bool $delete = false;

    public function __construct()
    {
        $faker = Factory::create();

        $gender = Gender::cases()[array_rand(Gender::cases())];

        $this->uuid = Uuid::next();
        $this->credential = new Credential(
            email: $faker->email,
            phone: random_int(11111111111, 99999999999),
        );
        $this->data = new Data(
            name: $faker->firstName($gender->value),
            surname: $faker->lastName,
            gender: Gender::cases()[array_rand(Gender::cases())],
            birthday: new \DateTimeImmutable($faker->date),
        );

        $this->date = new \DateTimeImmutable();
    }

    public function withUuid(Uuid $uuid): self
    {
        $clone = clone $this;
        $clone->uuid = $uuid;
        return $clone;
    }

    public function withCredential(Credential $credential): self
    {
        $clone = clone $this;
        $clone->credential = $credential;
        return $clone;
    }

    public function activated(): self
    {
        $clone = clone $this;
        $clone->active = true;
        return $clone;
    }

    public function blocked(): self
    {
        $clone = clone $this;
        $clone->block = true;
        return $clone;
    }

    public function deleted(): self
    {
        $clone = clone $this;
        $clone->delete = true;
        return $clone;
    }

    public function build(): User
    {
        $user = new User(
            uuid: $this->uuid,
            credential: $this->credential,
            data: $this->data,
            date: $this->date,
        );

        if ($this->active) {
            $user->activate(new \DateTimeImmutable());
        }

        if ($this->block) {
            $user->block(new \DateTimeImmutable());
        }

        if ($this->delete) {
            $user->delete(new \DateTimeImmutable());
        }

        return $user;
    }
}
