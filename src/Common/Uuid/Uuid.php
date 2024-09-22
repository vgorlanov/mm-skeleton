<?php

declare(strict_types=1);

namespace Common\Uuid;

final readonly class Uuid
{
    /**
     * @param non-empty-string $uuid
     */
    public function __construct(
        private string $uuid,
    ) {}

    /**
     * Генерация нового uuid
     *
     * @return static
     */
    public static function next(Version $version = Version::V7): self
    {
        return new self($version->make()->toString());
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->uuid;
    }

    /**
     * @param Uuid $other
     *
     * @return bool
     */
    public function isEqualTo(self $other): bool
    {
        return $this->toString() === $other->toString();
    }
}
