<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services\dto;


use Common\Uuid\Uuid;

final class CompleteDto
{
    public function __construct(
        public Uuid $uuid,
        public \DateTimeImmutable $date
    ) {}

}
