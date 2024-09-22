<?php

declare(strict_types=1);

namespace Modules\Company\Validators;

use Common\Validator;

final class CompanyValidator extends Validator
{
    public function name(): string
    {
        return 'company';
    }

    protected function getRules(): array
    {
        return [];
    }

    protected function getMessages(): array
    {
        return [];
    }

}
