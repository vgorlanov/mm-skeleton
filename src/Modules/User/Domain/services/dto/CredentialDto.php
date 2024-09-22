<?php

declare(strict_types=1);

namespace Modules\User\Domain\services\dto;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(description: 'Учётные данные', type: 'object')]
final class CredentialDto
{
    public function __construct(
        #[Property(title: 'Email', description: 'Адрес электронной почты', example: 'email@email.ru')]
        public string $email,
        #[Property(title: 'Phone', description: 'Телефон', example: 79163333333)]
        public ?int $phone,
    ) {}

}
