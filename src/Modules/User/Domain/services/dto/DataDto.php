<?php

declare(strict_types=1);

namespace Modules\User\Domain\services\dto;

use Modules\User\Domain\Gender;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(description: 'Информация о пользователе', type: 'object')]
final class DataDto
{
    public function __construct(
        #[Property(title: 'Name', description: 'Имя пользователя', example: 'Иван')]
        public ?string $name = null,
        #[Property(title: 'Surname', description: 'Фамилия пользователя', example: 'Иванов')]
        public ?string $surname = null,
        #[Property(title: 'Patronymic', description: 'Отчество пользователя', example: 'Иванович')]
        public ?string $patronymic = null,
        #[Property(title: 'Gender', description: 'Пол', example: 'male')]
        public ?Gender $gender = null,
        #[Property(title: 'Birthday', description: 'День рождения', example: '1999-12-12')]
        public ?\DateTimeImmutable $birthday = null,
    ) {}

    /**
     * @param array{name: string|null, surname: string|null, patronymic: string|null, gender: string|null, birthday: string|null} $params
     * @return self
     * @throws \Exception
     */
    public static function make(array $params): self
    {
        if (isset($params['gender'])) {
            $params['gender'] = Gender::tryFrom($params['gender']);
        }

        if(isset($params['birthday'])) {
            $params['birthday'] = new \DateTimeImmutable($params['birthday']);
        }

        return new self(...$params);
    }
}
