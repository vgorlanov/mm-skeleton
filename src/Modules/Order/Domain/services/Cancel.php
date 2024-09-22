<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services;


use Modules\Order\Domain\Order;
use Modules\Order\Domain\services\dto\CancelDto;
use Modules\Order\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class Cancel
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher
    ) {}

    /**
     * @param CancelDto $dto
     * @return Order
     * @throws \Modules\Order\Exceptions\OrderAlreadyCanceledException
     */
    public function execute(CancelDto $dto): Order
    {
        $order = $this->repository->get($dto->uuid);
        $order->cancel($dto->date);
        $this->repository->update($order);

        foreach ($order->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $order;
    }
}
