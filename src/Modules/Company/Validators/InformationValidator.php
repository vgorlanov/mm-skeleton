<?php

declare(strict_types=1);

namespace Modules\Company\Validators;

use Common\Validator;

final class InformationValidator extends Validator
{
    public function name(): string
    {
        return 'information';
    }

    protected function getRules(): array
    {
        return [
            'type' => ['required'],
        ];
    }

    protected function getMessages(): array
    {
        return [
            'type.required' => 'Укажите тип компании',
        ];
    }
}
