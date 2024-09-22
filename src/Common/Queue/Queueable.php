<?php

declare(strict_types=1);

namespace Common\Queue;

interface Queueable
{
    public function toJson(): string;

    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
