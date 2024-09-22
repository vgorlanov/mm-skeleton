<?php

declare(strict_types=1);

namespace Modules\Company\Validators;

use Common\Validator;

class AboutValidator extends Validator
{
    public function name(): string
    {
        return 'about';
    }

    public function getRules(): array
    {
        return [
            'name'    => ['required', 'string'],
            'country' => ['required', 'string'],
            'city'    => ['required', 'string'],
            'url'     => ['url'],
        ];
    }

    public function getMessages(): array
    {
        return [
            'name.required'    => 'Название компании обязательно к заполнению',
            'name.string'      => 'Название компании должно быть строкой',
            'country.required' => 'Укажите страну',
            'city.required'    => 'Укажите город',
            'url.url'          => 'Неверный формат URL',
        ];
    }
}
