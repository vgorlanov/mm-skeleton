<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services\dto;


use Common\Uuid\Uuid;

final readonly class CustomerDto
{
    public function __construct(
        public Uuid $uuid,
        public string $name,
        public string $email,
    ) { }
}
