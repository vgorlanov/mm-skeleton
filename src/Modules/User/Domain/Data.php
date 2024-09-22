<?php

declare(strict_types=1);

namespace Modules\User\Domain;

final readonly class Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $surname = null,
        public ?string $patronymic = null,
        public ?Gender $gender = null,
        public ?\DateTimeImmutable $birthday = null,
    ) {}

    /**
     * @return string
     * @throws \JsonException
     */
    public function toJSON(): string
    {
        return json_encode([
            'name'       => $this->name,
            'surname'    => $this->surname,
            'patronymic' => $this->patronymic,
            'gender'     => $this->gender?->value,
            'birthday'   => $this->birthday?->format('Y-m-d'),
        ], JSON_THROW_ON_ERROR);
    }
}
