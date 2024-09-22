<?php

declare(strict_types=1);

namespace Modules\User\Domain;

final readonly class Credential
{
    /**
     * @param string $email
     * @param int|null $phone
     */
    public function __construct(
        public string $email,
        public ?int $phone = null,
    ) {}

    /**
     * @throws \JsonException
     */
    public function toJSON(): string
    {
        return json_encode([
            'email' => $this->email,
            'phone' => $this->phone,
        ], JSON_THROW_ON_ERROR);
    }
}
