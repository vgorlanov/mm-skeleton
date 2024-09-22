<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Exchanges;

use Common\Queue\RabbitMQ\Dto\Bind;
use Common\Queue\RabbitMQ\Keys;
use Common\Queue\RabbitMQ\Queues\CompanyDomainEventsQueue;
use Common\Queue\RabbitMQ\Queues\ProductDomainEventsQueue;
use PhpAmqpLib\Wire\AMQPTable;

final class DomainEvents extends Exchange
{
    private const string EXCHANGE = 'DomainEvents.Exchange';

    private const string TYPE = 'topic';
    private const bool PASSIVE = false;
    private const bool DURABLE = false;
    private const bool AUTO_DELETE = true;
    private const bool INTERNAL = false;
    private const bool NOWAIT = false;

    private const int|null TICKET = null;

    private const AMQPTable|array ARGUMENTS = [];

    public function exchange(): string
    {
        return self::EXCHANGE;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function passive(): bool
    {
        return self::PASSIVE;
    }

    public function durable(): bool
    {
        return self::DURABLE;
    }

    public function autoDelete(): bool
    {
        return self::AUTO_DELETE;
    }

    public function internal(): bool
    {
        return self::INTERNAL;
    }

    public function nowait(): bool
    {
        return self::NOWAIT;
    }

    public function arguments()
    {
        return self::ARGUMENTS;
    }

    public function ticket(): int|null
    {
        return self::TICKET;
    }

    public function binds(): array
    {
        return [
            new Bind(new CompanyDomainEventsQueue(), Keys::COMPANY_DELETE),
            new Bind(new CompanyDomainEventsQueue(), Keys::COMPANY_SAVE),
            new Bind(new ProductDomainEventsQueue(), Keys::PRODUCT_DELETE),
            new Bind(new ProductDomainEventsQueue(), Keys::PRODUCT_SAVE),
        ];
    }

}
