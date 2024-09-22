<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services;

use Common\Uuid\Uuid;
use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\ProductDto;
use Modules\Product\Infrastructure\Repository;
use Modules\Product\Infrastructure\RepositoryProductAlreadyExistsException;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class CreateService
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @throws RepositoryProductAlreadyExistsException
     */
    public function execute(ProductDto $dto): Product
    {
        $product = new Product(
            uuid: Uuid::next(),
            company: $dto->company,
            title: $dto->title,
            body: $dto->body,
            date: new \DateTimeImmutable(),
            params: $dto->params,
            images: $dto->images,
        );

        $this->repository->add($product);

        foreach ($product->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $product;
    }
}
