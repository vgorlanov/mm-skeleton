<?php

declare(strict_types=1);

namespace Modules\User\Domain\services\dto;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(description: 'Пользователь', type: 'object')]
final class UserDto
{
    public function __construct(
        #[Property(title: 'credential')]
        public CredentialDto $credential,
        #[Property(title: 'data')]
        public DataDto $data,
    ) {}

}
