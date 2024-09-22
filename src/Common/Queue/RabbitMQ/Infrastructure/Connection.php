<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Infrastructure;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class Connection
{
    private static AMQPStreamConnection $connection;

    /**
     * @param array{host: string, port: int, user: string, pass: string}|null $config
     * @throws Exception
     */
    public static function instance(array $config = null): AMQPStreamConnection
    {
        if (!isset(self::$connection)) {


            // todo  если тут  сделать черех конфиг, то генерация swaggera  не срабатывает

            //            $config = $config ?: (array)config('services.broker.connection');

            //            self::$connection = new AMQPStreamConnection(
            //                host: $config['host'],
            //                port: $config['port'],
            //                user: $config['user'],
            //                password: $config['pass']
            //            );

            self::$connection = new AMQPStreamConnection(
                host: 'project-rabbit',
                port: 5672,
                user: 'vlad',
                password: 123,
            );
        }


        //        RABBITMQ_HOST=project-rabbit
        //RABBITMQ_NODE_HOST_PORT=5672
        //RABBITMQ_MANAGEMENT_HTTP_HOST_PORT=15672
        //RABBITMQ_MANAGEMENT_HTTPS_HOST_PORT=15671
        //RABBITMQ_DEFAULT_USER=vlad
        //RABBITMQ_DEFAULT_PASS=123

        // todo проверить срабатывает ли это
        register_shutdown_function([self::class, 'close']);

        return self::$connection;
    }

    /**
     * @throws Exception
     */
    public static function close(): void
    {
        self::$connection->close();
    }
}
