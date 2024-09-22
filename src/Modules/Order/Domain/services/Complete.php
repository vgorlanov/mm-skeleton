<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services;


use Modules\Order\Domain\Order;
use Modules\Order\Domain\services\dto\CompleteDto;
use Modules\Order\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class Complete
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher
    ) {}

    public function execute(CompleteDto $dto): Order
    {
        $order = $this->repository->get($dto->uuid);
        $order->complete($dto->date);
        $this->repository->update($order);

        foreach ($order->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $order;
    }
}
