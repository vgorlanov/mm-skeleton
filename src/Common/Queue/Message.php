<?php

declare(strict_types=1);

namespace Common\Queue;

use Common\Uuid\Uuid;

final class Message
{
    private Uuid $uuid;

    private \DateTimeImmutable $date;

    /**
     * @param string $name
     * @param array<mixed>|string|null $body
     * @param Uuid|null $uuid
     * @param \DateTimeImmutable|null $date
     */
    public function __construct(
        private readonly string $name,
        private readonly array|string|null $body = null,
        ?Uuid $uuid = null,
        ?\DateTimeImmutable $date = null,
    ) {
        $this->uuid = $uuid !== null ? $uuid : Uuid::next();
        $this->date = $date !== null ? $date : new \DateTimeImmutable();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<mixed>|string|null
     */
    public function getBody(): array|string|null
    {
        return $this->body;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @throws \JsonException
     */
    public function toJson(): string
    {
        return json_encode([
            'uuid' => $this->uuid->toString(),
            'name' => $this->name,
            'body' => $this->body,
            'date' => $this->date,
        ], JSON_THROW_ON_ERROR);
    }
}
