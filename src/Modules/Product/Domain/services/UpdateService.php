<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services;

use Common\Uuid\Uuid;
use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\ProductDto;
use Modules\Product\Infrastructure\Repository;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class UpdateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @param Uuid $uuid
     * @param ProductDto $dto
     * @return Product
     * @throws RepositoryProductNotFoundException
     */
    public function execute(Uuid $uuid, ProductDto $dto): Product
    {
        $product = $this->repository->get($uuid);

        $product->update(
            title: $dto->title,
            body: $dto->body,
            params: $dto->params,
            images: $dto->images,
        );

        $this->repository->update($product);

        foreach ($product->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $product;
    }
}
