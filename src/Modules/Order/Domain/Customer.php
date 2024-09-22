<?php

declare(strict_types=1);

namespace Modules\Order\Domain;

use Common\Uuid\Uuid;

final readonly class Customer
{
    public function __construct(
        public Uuid $uuid,
        public string $name,
        public string $email,
    ) {}
}
