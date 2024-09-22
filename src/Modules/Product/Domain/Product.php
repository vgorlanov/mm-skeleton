<?php

declare(strict_types=1);

namespace Modules\Product\Domain;

use Common\EntityAggregate;
use Common\Events\DomainEventPublisher;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\Product\Domain\events\Deleted;
use Modules\Product\Domain\events\Published;
use Modules\Product\Domain\events\Restored;
use Modules\Product\Domain\events\UnPublished;
use Modules\Product\Domain\events\Updated;
use Modules\Product\Exceptions\ProductDeletedException;
use Modules\Product\Exceptions\ProductPublishedException;

final class Product implements EntityAggregate
{
    private bool $published = false;

    private ?DateTimeImmutable $deleted = null;

    /**
     * @param Uuid $uuid
     * @param Uuid $company
     * @param string $title
     * @param string $body
     * @param DateTimeImmutable $date
     * @param array<string, string>|null $params
     * @param array<string>|null $images
     */
    public function __construct(
        private readonly Uuid $uuid,
        private readonly Uuid $company,
        private string $title,
        private string $body,
        private readonly DateTimeImmutable $date,
        private ?array $params = null,
        private ?array $images = null,
    ) {}


    /**
     * @param string $title
     * @param string $body
     * @param array<string, string>|null $params
     * @param array<string>|null $images
     * @return void
     */
    public function update(
        string $title,
        string $body,
        ?array $params = [],
        ?array $images = [],
    ): void {
        $this->title = $title;
        $this->body = $body;
        $this->images = $images;
        $this->params = $params;

        $this->events()->publish(
            new Updated(
                uuid: $this->uuid,
                title: $title,
                body: $body,
                images: $images,
                params: $params,
            ),
        );
    }

    /**
     * @return void
     * @throws ProductPublishedException
     */
    public function publish(): void
    {
        if ($this->published) {
            ProductPublishedException::already();
        }

        $this->published = true;
        $this->events()->publish(new Published($this->uuid));
    }

    /**
     * @return void
     * @throws ProductPublishedException
     */
    public function unPublish(): void
    {
        if ($this->published === false) {
            ProductPublishedException::not();
        }

        $this->published = false;
        $this->events()->publish(new UnPublished($this->uuid));
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param DateTimeImmutable|null $date
     * @return void
     * @throws ProductDeletedException
     */
    public function delete(DateTimeImmutable $date = null): void
    {
        if ($this->deleted instanceof DateTimeImmutable) {
            ProductDeletedException::already();
        }

        $this->deleted = $date ?: new DateTimeImmutable();
        $this->events()->publish(new Deleted($this->uuid));
    }

    public function isDeleted(): DateTimeImmutable|false
    {
        return $this->deleted instanceof DateTimeImmutable ? $this->deleted : false;
    }

    /**
     * @return void
     * @throws ProductDeletedException
     */
    public function restore(): void
    {
        if ($this->deleted === null) {
            ProductDeletedException::not();
        }

        $this->deleted = null;
        $this->events()->publish(new Restored($this->uuid));
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
    public function getParams(): array|null
    {
        return $this->params;
    }

    /**
     * @return string[]|null
     */
    public function getImages(): array|null
    {
        return $this->images;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getCompany(): Uuid
    {
        return $this->company;
    }

    public function events(): DomainEventPublisher
    {
        return (DomainEventPublisher::instance())->setEntity($this);
    }
}
