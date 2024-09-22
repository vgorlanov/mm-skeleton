<?php

declare(strict_types=1);

namespace Modules\Company\Domain;

/**
 * @phpstan-type CompanyInformationT array{type: Type, name: string|null, inn: int|null, kpp: int|null,
 * address: string|null, real: string|null, fio: string|null, phone: int|null, info: string|null}
 */
final readonly class Information
{
    public function __construct(
        public Type $type,
        public ?string $name = null,      // наименование (юридическое название)
        public ?int $inn = null,
        public ?int $kpp = null,
        public ?string $address = null,   // юридический адрес
        public ?string $real = null,      // фактический адрес
        public ?string $fio = null,
        public ?int $phone = null,
        public ?string $info = null,      // реквизиты если иностранная компания
    ) {}

    /**
     * @return string
     * @throws \JsonException
     */
    public function toJSON(): string
    {
        return json_encode((array) $this, JSON_THROW_ON_ERROR);
    }
}
