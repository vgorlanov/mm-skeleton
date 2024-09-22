<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\Repository;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\User\Domain\Credential;
use Modules\User\Infrastructure\Repository;
use Modules\User\Infrastructure\RepositoryUserAlreadyExistsException;
use Modules\User\Infrastructure\RepositoryUserNotFoundException;
use Tests\TestCase;
use Tests\Unit\modules\User\UserBuilder;

abstract class Base extends TestCase
{
    abstract public function repository(): Repository;

    public function test_get_exception(): void
    {
        $this->expectException(RepositoryUserNotFoundException::class);
        $this->repository()->get(Uuid::next());
    }

    public function test_add_success(): void
    {
        $new = (new UserBuilder())->build();

        $this->repository()->add($new);

        $user = $this->repository()->get($new->getUuid());

        $this->assertEquals($user->getUuid(), $new->getUuid());
        $this->assertEquals($user->getCredential(), $new->getCredential());
        $this->assertEquals($user->getDate(), $new->getDate());
        $this->assertEquals($user->getData(), $new->getData());
    }

    public function test_add_exception(): void
    {
        $user = (new UserBuilder())->build();

        $this->repository()->add($user);

        $this->expectException(RepositoryUserAlreadyExistsException::class);
        $this->repository()->add($user);
    }

    public function test_update_success(): void
    {
        $uuid = Uuid::next();

        $user = (new UserBuilder())->withUuid($uuid)->build();
        $update = (new UserBuilder())->withUuid($uuid)->build();

        $this->repository()->add($user);
        $this->repository()->update($update);

        $updated = $this->repository()->get($update->getUuid());

        $this->assertEquals($updated->getUuid(), $user->getUuid());
        $this->assertNotEquals($updated->getData(), $user->getData());
        $this->assertNotEquals($updated->getCredential(), $user->getCredential());
    }

    public function test_update_exception(): void
    {
        $user = (new UserBuilder())->build();
        $this->expectException(RepositoryUserNotFoundException::class);
        $this->repository()->update($user);
    }

    public function test_remove_success(): void
    {
        $user = (new UserBuilder())->build();
        $this->repository()->add($user);
        $this->repository()->remove($user);

        $this->expectException(RepositoryUserNotFoundException::class);
        $this->repository()->update($user);
    }

    public function test_remove_exception(): void
    {
        $user = (new UserBuilder())->build();

        $this->expectException(RepositoryUserNotFoundException::class);
        $this->repository()->remove($user);
    }

    /**
     * @throws \Exception
     */
    public function test_getByEmail_success(): void
    {
        $faker = Factory::create();

        $credential = new Credential(
            email: $email = $faker->email,
            phone: random_int(11111111111, 99999999999),
        );
        $user = (new UserBuilder())->withCredential($credential)->build();

        $this->repository()->add($user);

        $result = $this->repository()->getByEmail($email);

        $this->assertEquals($user, $result);
    }

    public function test_getByEmail_not_fount_exception(): void
    {
        $user = (new UserBuilder())->build();

        $this->expectException(RepositoryUserNotFoundException::class);
        $this->repository()->getByEmail($user->getCredential()->email);
    }
}
