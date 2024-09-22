<?php

declare(strict_types=1);

namespace Modules\Product\Domain\events;

use Common\Events\Attributes\Listener;
use Common\Events\DomainEvent;
use Common\Events\Listeners\PersistDomainEventListener;
use Common\Events\Listeners\QueueEventListener;
use Common\Queue\Queueable;
use Common\Uuid\Uuid;
use DateTimeImmutable;

#[Listener(PersistDomainEventListener::class)]
#[Listener(QueueEventListener::class)]
final class Updated extends DomainEvent implements Queueable
{
    private DateTimeImmutable $occurred;

    /**
     * @param Uuid $uuid
     * @param string $title
     * @param string $body
     * @param array<string, string>|null $params
     * @param array<string>|null $images
     */
    public function __construct(
        private readonly Uuid $uuid,
        private readonly string $title,
        private readonly string $body,
        private readonly ?array $images = [],
        private readonly ?array $params = [],
    ) {
        parent::__construct();

        $this->occurred = new DateTimeImmutable();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurred;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string[]|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @return string[]|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            'uuid'       => $this->uuid->toString(),
            'occurredOn' => $this->occurred,
            'title'      => $this->title,
            'body'       => $this->body,
            'params'     => $this->params,
            'images'     => $this->images,
        ];
    }
}
