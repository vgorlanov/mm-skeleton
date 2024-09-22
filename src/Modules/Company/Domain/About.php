<?php

declare(strict_types=1);

namespace Modules\Company\Domain;

final readonly class About
{
    public function __construct(
        public string $name,
        public string $country,
        public string $city,
        public ?string $url = null,
        public ?string $alias = null,
        public ?string $image = null,
        public ?string $about = null,
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
