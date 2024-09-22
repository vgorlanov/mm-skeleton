<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\PublishDto;
use Modules\Product\Exceptions\ProductPublishedException;
use Modules\Product\Infrastructure\Repository;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class PublishService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param PublishDto $dto
     * @return Product
     * @throws RepositoryProductNotFoundException
     * @throws ProductPublishedException
     */
    public function execute(PublishDto $dto): Product
    {
        $product = $this->repository->get($dto->uuid);
        $product->publish();
        $this->repository->update($product);

        foreach ($product->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $product;
    }
}
