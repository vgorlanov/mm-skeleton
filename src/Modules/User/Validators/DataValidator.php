<?php

declare(strict_types=1);

namespace Modules\User\Validators;

use Common\Validator;

final class DataValidator extends Validator
{
    public function name(): string
    {
        return 'data';
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
