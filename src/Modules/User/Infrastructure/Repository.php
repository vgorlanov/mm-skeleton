<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure;

use Common\Uuid\Uuid;
use Modules\User\Domain\User;

interface Repository
{
    public function get(Uuid $uuid): User;

    public function add(User $user): void;

    public function update(User $user): void;

    public function remove(User $user): void;

    public function getByEmail(string $email): User;

}
