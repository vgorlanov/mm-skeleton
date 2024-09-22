<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\RestoreDto;
use Modules\Product\Exceptions\ProductDeletedException;
use Modules\Product\Infrastructure\Repository;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class RestoreService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param RestoreDto $dto
     * @return Product
     * @throws RepositoryProductNotFoundException
     * @throws ProductDeletedException
     */
    public function execute(RestoreDto $dto): Product
    {
        $product = $this->repository->get($dto->uuid);
        $product->restore();
        $this->repository->update($product);

        foreach ($product->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $product;
    }
}
