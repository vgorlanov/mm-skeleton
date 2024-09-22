<?php

declare(strict_types=1);

namespace Modules\User\Validators;

use Common\Validator;

final class UserValidator extends Validator
{
    public function name(): string
    {
        return 'user';
    }

    protected function getMessages(): array
    {
        return [];
    }

    protected function getRules(): array
    {
        return [];
    }
}
