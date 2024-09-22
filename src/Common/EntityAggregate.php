<?php

declare(strict_types=1);

namespace Common;

use Common\Events\DomainEventPublisher;
use Common\Uuid\Uuid;

interface EntityAggregate
{
    public function getUuid(): Uuid;

    public function events(): DomainEventPublisher;
}
