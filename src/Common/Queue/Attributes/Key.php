<?php

declare(strict_types=1);

namespace Common\Queue\Attributes;

use Attribute;
use Common\Queue\RabbitMQ\Keys;

#[Attribute]
final readonly class Key
{
    public function __construct(
        public Keys $key,
    ) {}

}
