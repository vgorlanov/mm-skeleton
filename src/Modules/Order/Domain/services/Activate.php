<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services;


use Modules\Order\Domain\Order;
use Modules\Order\Domain\services\dto\ActivateDto;
use Modules\Order\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class Activate
{
    public function __construct(
        private Repository $repository,
        private EventDispatcherInterface $dispatcher
    ) {}

    /**
     * @param ActivateDto $dto
     * @return Order
     * @throws \Modules\Order\Exceptions\OrderAlreadyActivatedException
     */
    public function execute(ActivateDto $dto): Order
    {
        $order = $this->repository->get($dto->uuid);
        $order->activate($dto->date);
        $this->repository->update($order);

        foreach ($order->events()->release() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $order;
    }
}
