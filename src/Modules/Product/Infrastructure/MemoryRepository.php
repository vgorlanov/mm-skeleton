<?php

declare(strict_types=1);

namespace Modules\Product\Infrastructure;

use Common\Uuid\Uuid;
use Modules\Product\Domain\Product;

final class MemoryRepository implements Repository
{
    /**
     * @var array<Product>
     */
    private array $items = [];

    public function get(Uuid $uuid): Product
    {
        if (array_key_exists($uuid->toString(), $this->items)) {
            return clone $this->items[$uuid->toString()];
        }

        throw new RepositoryProductNotFoundException($uuid->toString());
    }

    public function add(Product $product): void
    {
        $uuid = $product->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            throw new RepositoryProductAlreadyExistsException($uuid);
        }

        $this->items[$uuid] = $product;
    }

    public function update(Product $product): void
    {
        $uuid = $product->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            $this->items[$uuid] = $product;
        } else {
            throw new RepositoryProductNotFoundException($uuid);
        }
    }

    public function remove(Product $product): void
    {
        $uuid = $product->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            unset($this->items[$uuid]);
        } else {
            throw new RepositoryProductNotFoundException($uuid);
        }
    }
}
