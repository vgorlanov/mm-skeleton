<?php

declare(strict_types=1);

namespace Common\Queue\Commands;

use Common\Queue\RabbitMQ\Exchanges\Exchange;
use Common\Queue\RabbitMQ\Infrastructure\Binder;
use Common\Queue\RabbitMQ\Infrastructure\Configuration;
use Common\Queue\RabbitMQ\Queues\Queue;
use Illuminate\Console\Command;

final class RabbitMQInitializationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание, маппинг очередей, exchange-й Rabbit-а';

    public function handle(): void
    {
        $configuration = Configuration::getInstance();

        $this->makeQueues($configuration->queues());
        $this->makeExchanges($configuration->exchanges());
        $this->makeBinds($configuration->exchanges());
    }

    /**
     * @param Queue[] $queues
     * @return void
     */
    private function makeQueues(array $queues): void
    {
        foreach ($queues as $queue) {
            $queue->make();
        }
    }

    /**
     * @param Exchange[] $exchanges
     * @return void
     */
    private function makeExchanges(array $exchanges): void
    {
        foreach ($exchanges as $exchange) {
            $exchange->make();
        }
    }

    /**
     * @param Exchange[] $exchanges
     * @return void
     */
    private function makeBinds(array $exchanges): void
    {
        $binder = new Binder();
        foreach ($exchanges as $exchange) {
            foreach ($exchange->binds() as $bind) {
                $binder->bind($bind->queue, $exchange, $bind->key);
            }
        }
    }
}
