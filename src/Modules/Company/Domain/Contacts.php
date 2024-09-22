<?php

declare(strict_types=1);

namespace Modules\Company\Domain;

/**
 * @phpstan-type CompanyContactsT array{name: string, email: string, phone: int, position: string, comment: string|null}
 */
final readonly class Contacts
{
    public function __construct(
        public string $name,
        public string $position, // должность
        public string $email,
        public int $phone,
        public ?string $comment = null,
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
