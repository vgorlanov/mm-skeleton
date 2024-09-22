<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\UnPublishDto;
use Modules\Product\Exceptions\ProductPublishedException;
use Modules\Product\Infrastructure\Repository;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class UnPublishService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param UnPublishDto $dto
     * @return Product
     * @throws RepositoryProductNotFoundException
     * @throws ProductPublishedException
     */
    public function execute(UnPublishDto $dto): Product
    {
        $product = $this->repository->get($dto->uuid);
        $product->unPublish();
        $this->repository->update($product);

        foreach ($product->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $product;
    }
}
