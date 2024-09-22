<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services\dto;

final readonly class StatusDto
{
    /**
     * @param string $name
     * @param string $code
     */
    public function __construct(
        public string $name,
        public string $code,
    ) {}
}
