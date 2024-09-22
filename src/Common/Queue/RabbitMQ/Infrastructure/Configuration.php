<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Infrastructure;

use Common\Queue\RabbitMQ\Exchanges\Exchange;
use Common\Queue\RabbitMQ\Queues\Queue;

/**
 * @phpstan-type ConfigT array{queues: array{string}, exchanges: array{string}}
 */
final class Configuration
{
    private static ?Configuration $instance = null;

    /**
     * @var Exchange[]
     */
    private array $exchanges = [];

    /**
     * @var Queue[]
     */
    private array $queues = [];

    /**
     * @var ConfigT
     */
    private array $config;

    /**
     * @param ConfigT $config
     */
    private function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __clone(): void
    {
        // skip
    }

    public function __wakeup(): void
    {
        // skip
    }

    /**
     * @param ConfigT|null $config
     * @return self
     */
    public static function getInstance(?array $config = null): self
    {
        /** @var ConfigT $config */
        $config = $config !== null ? $config : (array) config('rabbitmq');

        if (self::$instance === null) {
            self::$instance = new Configuration($config);
        }

        return self::$instance;
    }

    /**
     * @return Exchange[]
     */
    public function exchanges(): array
    {
        if (count($this->exchanges) === 0) {
            foreach ($this->config['exchanges'] as $value) {
                /** @var Exchange $exchange */
                $exchange = new $value();
                $this->exchanges[$exchange->exchange()] = $exchange;
            }
        }

        return $this->exchanges;
    }

    /**
     * @return Queue[]
     */
    public function queues(): array
    {
        if (count($this->queues) === 0) {
            foreach ($this->config['queues'] as $value) {
                /** @var Queue $queue */
                $queue = new $value();
                $this->queues[$queue->queue()] = $queue;
            }
        }
        return $this->queues;
    }
}
