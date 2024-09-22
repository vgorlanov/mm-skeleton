<?php

declare(strict_types=1);

namespace Common\Events\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
final class Listener
{
    public function __construct(
        public string $event,
    ) {}
}
