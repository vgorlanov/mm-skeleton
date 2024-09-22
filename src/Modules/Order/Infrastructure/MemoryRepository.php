<?php

declare(strict_types=1);

namespace Modules\Order\Infrastructure;

use Common\Uuid\Uuid;
use Modules\Order\Domain\Order;
use Modules\Order\Exceptions\RepositoryOrderExistsException;
use Modules\Order\Exceptions\RepositoryOrderNotFoundException;

final class MemoryRepository implements Repository
{
    /**
     * @var Order[]
     */
    private array $items = [];

    public function get(Uuid $uuid): Order
    {
        if (array_key_exists($uuid->toString(), $this->items)) {
            return clone $this->items[$uuid->toString()];
        }

        throw new RepositoryOrderNotFoundException($uuid);
    }

    public function add(Order $order): void
    {
        if (array_key_exists($order->getUuid()->toString(), $this->items)) {
            throw new RepositoryOrderExistsException($order->getUuid());
        }
        $this->items[$order->getUuid()->toString()] = $order;
    }

    public function update(Order $order): void
    {
        $uuid = $order->getUuid()->toString();
        if(array_key_exists($uuid, $this->items)) {
            $this->items[$uuid] = $order;
        } else {
            throw new RepositoryOrderNotFoundException($order->getUuid());
        }
    }

    public function remove(Order $order): void
    {
        $uuid = $order->getUuid()->toString();
        if(array_key_exists($uuid, $this->items)) {
            unset($this->items[$uuid]);
        } else {
            throw new RepositoryOrderNotFoundException($order->getUuid());
        }
    }
}
