<?php

declare(strict_types=1);

namespace Modules\Company\Validators;

use Common\Validator;

final class ContactsValidator extends Validator
{
    protected array $rules = [
        'name'     => ['required'],
        'position' => ['required'],
        'email'    => ['required', 'email'],
    ];

    public function name(): string
    {
        return 'contacts';
    }

    protected function getRules(): array
    {
        return [
            'name'     => ['required'],
            'position' => ['required'],
            'email'    => ['required', 'email'],
        ];
    }

    protected function getMessages(): array
    {
        return [
            'name.required'     => 'Введите имя',
            'position.required' => 'Укажите должность',
            'email.required'    => 'Укажите email',
            'phone.required'    => 'Укажите телефон',
        ];
    }


}
