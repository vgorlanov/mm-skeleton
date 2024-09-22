<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure;

use Common\Uuid\Uuid;
use Modules\User\Domain\User;

final class MemoryRepository implements Repository
{
    /**
     * @var array<User>
     */
    private array $items = [];

    public function get(Uuid $uuid): User
    {
        if (array_key_exists($uuid->toString(), $this->items)) {
            return clone $this->items[$uuid->toString()];
        }

        throw new RepositoryUserNotFoundException($uuid->toString());
    }

    public function add(User $user): void
    {
        $uuid = $user->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            throw new RepositoryUserAlreadyExistsException($uuid);
        }

        $this->items[$uuid] = $user;
    }

    public function update(User $user): void
    {
        $uuid = $user->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            $this->items[$uuid] = $user;
        } else {
            throw new RepositoryUserNotFoundException($uuid);
        }
    }

    public function remove(User $user): void
    {
        $uuid = $user->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            unset($this->items[$uuid]);
        } else {
            throw new RepositoryUserNotFoundException($uuid);
        }
    }

    public function getByEmail(string $email): User
    {
        foreach ($this->items as $key => $item) {
            if ($item->getCredential()->email === $email) {
                return $this->items[$key];
            }
        }

        throw new RepositoryUserNotFoundException($email);
    }
}
