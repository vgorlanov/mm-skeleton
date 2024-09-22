<?php

declare(strict_types=1);

namespace Modules\Product\Infrastructure;

use Common\Uuid\Uuid;
use Modules\Product\Domain\Product;

interface Repository
{
    /**
     * @param Uuid $uuid
     * @return Product
     * @throws RepositoryProductNotFoundException
     */
    public function get(Uuid $uuid): Product;

    /**
     * @param Product $product
     * @return void
     * @throws RepositoryProductAlreadyExistsException
     */
    public function add(Product $product): void;

    /**
     * @param Product $product
     * @return void
     * @throws RepositoryProductNotFoundException
     */
    public function update(Product $product): void;

    /**
     * @param Product $product
     * @return void
     * @throws RepositoryProductNotFoundException
     */
    public function remove(Product $product): void;
}
