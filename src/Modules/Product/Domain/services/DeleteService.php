<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\DeleteDto;
use Modules\Product\Exceptions\ProductDeletedException;
use Modules\Product\Infrastructure\Repository;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class DeleteService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param DeleteDto $dto
     * @return Product
     * @throws ProductDeletedException
     * @throws RepositoryProductNotFoundException
     */
    public function execute(DeleteDto $dto): Product
    {
        $product = $this->repository->get($dto->uuid);
        $product->delete($dto->date);
        $this->repository->update($product);

        foreach ($product->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $product;
    }
}
