<?php

declare(strict_types=1);

namespace Modules\User\Validators;

use Common\Validator;
use Modules\User\Infrastructure\Rules\EmailUnique;

final class CredentialValidator extends Validator
{
    public function name(): string
    {
        return 'credential';
    }

    protected function getMessages(): array
    {
        return [
            'email.required' => 'Укажите адрес электронной почты',
            'email.email'    => 'Не верный формат адреса электронной почты',
        ];
    }

    protected function getRules(): array
    {
        return [
            'email' => ['bail', 'required', 'email', new EmailUnique()],
        ];
    }
}
