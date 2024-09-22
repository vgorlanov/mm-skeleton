<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services\dto;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(description: 'Контактное лицо', type: 'object')]
final readonly class ContactsDto
{
    public function __construct(
        #[Property(title: 'Name', description: 'ФИО', example: 'Иванов Иван Иванович')]
        public string $name,
        #[Property(title: 'Position', description: 'Должность', example: 'Генеральный директор')]
        public string $position, // должность
        #[Property(title: 'Email', example: 'email@mail.ru')]
        public string $email,
        #[Property(title: 'Phone', example: '9999999999')]
        public int $phone,
        #[Property(title: 'Comment', description: 'Дополнительный комментарий')]
        public ?string $comment = null,
    ) {}

}
