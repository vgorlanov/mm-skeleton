<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services;


use Common\Uuid\Uuid;
use Modules\Order\Domain\Customer;
use Modules\Order\Domain\Order;
use Modules\Order\Domain\Price;
use Modules\Order\Domain\Product;
use Modules\Order\Domain\services\dto\OrderDto;
use Modules\Order\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class Create
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher
    ) {}

    public function execute(OrderDto $dto): Order
    {
        $order = new Order(
            uuid: Uuid::next(),
            company: $dto->company,
            customer: new Customer(...(array) $dto->customer),
            date: new \DateTimeImmutable()
        );

        $this->addProducts($order, $dto->products);

        $this->repository->add($order);

        foreach ($order->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $order;
    }

    private function addProducts(Order $order, array $products): void
    {
        foreach ($products as $product) {
            $order->addProduct(
                new Product(
                    uuid: $product->uuid,
                    name: $product->name,
                    price: Price::make($product->price),
                    quantity: $product->quantity
                )
            );
        }
    }

}
