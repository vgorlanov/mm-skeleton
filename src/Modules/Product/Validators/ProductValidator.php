<?php

declare(strict_types=1);

namespace Modules\Product\Validators;

use Common\Rules\CompanyExists;
use Common\Validator;

final class ProductValidator extends Validator
{
    public function name(): string
    {
        return 'product';
    }

    /**
     * @return string[]
     */
    protected function getMessages(): array
    {
        return [
            'company.required' => 'Укажите uuid компании',
            'company.uuid'     => 'Не верный формат uuid компании',
            'title.required'   => 'Введите название продукта',
            'body.required'    => 'Заполните описание продукта',
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function getRules(): array
    {
        return [
            'company' => ['bail', 'required', 'uuid', new CompanyExists()],
            'title'   => ['required', 'string'],
            'body'    => ['required', 'string'],
        ];
    }
}
