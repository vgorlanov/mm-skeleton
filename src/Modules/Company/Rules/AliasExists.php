<?php

declare(strict_types=1);

namespace Modules\Company\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Company\Infrastructure\Repository;

final class AliasExists implements ValidationRule
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = app(Repository::class);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($value !== null && $this->repository->hasAlias($value)) {
            $fail('Такой alias уже используется');
        }
    }
}
