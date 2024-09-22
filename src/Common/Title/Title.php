<?php

declare(strict_types=1);

namespace Common\Title;

use Attribute;

#[Attribute]
final readonly class Title
{
    public function __construct(
        public string $title,
    ) {}

}
