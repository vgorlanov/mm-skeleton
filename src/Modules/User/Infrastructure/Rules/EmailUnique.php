<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Rules;

use Closure;
use Common\Uuid\Uuid;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\Infrastructure\Repository;

final class EmailUnique implements ValidationRule
{
    private Repository $repository;

    private const MESSAGE = 'Данный адрес электронной почты уже используется';

    /**
     * @param Uuid|null $uuid
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(
        private ?Uuid $uuid = null,
    ) {
        $this->repository = app()->make(Repository::class);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $user = $this->repository->getByEmail($value);
            if ($this->uuid !== null && !$user->getUuid()->isEqualTo($this->uuid)) {
                $fail(self::MESSAGE);
            }

            if ($this->uuid === null) {
                $fail(self::MESSAGE);
            }
        } catch (\Exception $e) {
        }
    }
}
