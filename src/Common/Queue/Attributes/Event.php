<?php

declare(strict_types=1);

namespace Common\Queue\Attributes;

#[\Attribute]
final readonly class Event
{
    /**
     * @param class-string<\Common\Queue\Queueable> $eventName
     */
    public function __construct(public string $eventName) {}
}
