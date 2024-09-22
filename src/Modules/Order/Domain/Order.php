<?php

declare(strict_types=1);

namespace Modules\Order\Domain;

use Common\EntityAggregate;
use Common\Events\DomainEventPublisher;
use Common\Status\HasStatus;
use Common\Status\Status as CommonStatus;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\Order\Domain\events\Activated;
use Modules\Order\Domain\events\Cancel;
use Modules\Order\Domain\events\Complete;
use Modules\Order\Domain\events\Created;
use Modules\Order\Domain\Operations\Multiply;
use Modules\Order\Domain\Operations\Plus;
use Modules\Order\Exceptions\OrderAlreadyActivatedException;
use Modules\Order\Exceptions\OrderAlreadyCanceledException;
use Modules\Order\Exceptions\OrderAlreadyCompleteException;
use Modules\Order\Exceptions\OrderProductNotExists;

/**
 * @phpstan-import-type CollectionStatuses from CommonStatus
 * @immutable
 */
final class Order implements EntityAggregate, HasStatus
{
    /** @var array<CollectionStatuses> */
    private array $statuses = [];

    /**
     * @var Product[]
     */
    private array $products = [];

    /**
     * @param Uuid $uuid
     * @param Uuid $company
     * @param Customer $customer
     * @param DateTimeImmutable $date
     */
    public function __construct(
        private readonly Uuid $uuid,
        private readonly Uuid $company,
        private readonly Customer $customer,
        private readonly DateTimeImmutable $date,
    ) {
        $this->statuses = $this->status()->add(Status::NEW, new DateTimeImmutable());

        $this->events()->publish(new Created(
            uuid: $this->uuid,
            company: $this->company,
            customer: $this->customer,
        ));
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws OrderAlreadyActivatedException
     */
    public function activate(DateTimeImmutable $date): void
    {
        if($this->status()->current() === Status::ACTIVE) {
            throw new OrderAlreadyActivatedException();
        }

        $this->status()->add(Status::ACTIVE, $date);
        $this->events()->publish(new Activated($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws OrderAlreadyCanceledException
     */
    public function cancel(DateTimeImmutable $date): void
    {
        if($this->status()->current() === Status::CANCEL) {
            throw new OrderAlreadyCanceledException();
        }

        $this->status()->add(Status::CANCEL, $date);
        $this->events()->publish(new Cancel($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws OrderAlreadyCompleteException
     */
    public function complete(DateTimeImmutable $date): void
    {
        if($this->status()->current() === Status::COMPLETE) {
            throw new OrderAlreadyCompleteException();
        }

        $this->status()->add(Status::COMPLETE, $date);
        $this->events()->publish(new Complete($this->uuid));
    }

    public function addProduct(Product $product): void
    {
        $uuid = $product->uuid->toString();
        if(array_key_exists($uuid, $this->products)) {
            $ar = (array) $product;
            $ar['quantity']++;
            $this->products[$uuid] = new Product(...$ar);
        } else {
            $this->products[$uuid] = $product;
        }
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return array_values($this->products);
    }

    public function deleteProduct(Product $product): void
    {
        $this->products = array_filter($this->products, fn($item) => !$item->uuid->isEqualTo($product->uuid));
    }

    public function updateProduct(Product $product): void
    {
        $uuid = $product->uuid->toString();
        if(array_key_exists($uuid, $this->products)) {
            $this->products[$uuid] = $product;
        } else {
            throw new OrderProductNotExists();
        }
    }

    public function amount(): Price
    {
        $price = new Price(main: 0, fractional: 0);
        foreach ($this->products as $product) {
            if($product->quantity > 1) {
                $result = (new Multiply($product->price, $product->quantity))->calc();
                $price = (new Plus($price, $result))->calc();
            }
        }
        return $price;
    }

    public function getCompany(): Uuid
    {
        return $this->company;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function status(): CommonStatus
    {
        return new CommonStatus($this->statuses);
    }

    public function events(): DomainEventPublisher
    {
        return (DomainEventPublisher::instance())->setEntity($this);
    }
}
