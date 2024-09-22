<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services\dto;

use Modules\Company\Domain\Type;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(description: 'Юридическая информация', type: 'object')]
final readonly class InformationDto
{
    public function __construct(
        #[Property(title: 'Type', example: 'Тип компании')]
        public Type $type,
        #[Property(title: 'Name', example: 'Название компании (юридическое название)')]
        public ?string $name = null,
        #[Property(title: 'Inn', example: 'ИНН')]
        public ?int $inn = null,
        #[Property(title: 'Kpp', example: 'КПП')]
        public ?int $kpp = null,
        #[Property(title: 'Address', example: 'Адрес (юридический)')]
        public ?string $address = null,
        #[Property(title: 'Real', example: 'Адрес (адрес)')]
        public ?string $real = null,
        #[Property(title: 'Fio', example: 'ФИО')]
        public ?string $fio = null,
        #[Property(title: 'Phone', example: 'Телефон')]
        public ?int $phone = null,
        #[Property(title: 'Info', example: 'Реквизиты если иностранная компания')]
        public ?string $info = null,
    ) {}

    /**
     * @param array{type: string, name: string|null, inn: int|null, kpp: int|null, address: string|null, real: string|null, fio:string|null, phone:int|null,
     *     info: string|null} $params
     * @return self
     */
    public static function make(array $params): self
    {
        $params['type'] = Type::from($params['type']);

        return new self(...$params);
    }
}
