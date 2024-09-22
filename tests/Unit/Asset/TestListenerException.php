<?php

declare(strict_types=1);

namespace Tests\Unit\Asset;

use Common\Events\Exceptions\InvalidListenerException;
use Common\Events\Listeners\Listener;

final class TestListenerException implements Listener
{
    public function handle(object $event): void
    {
        throw new InvalidListenerException('test');
    }

    public function rollback(object $event): void {}
}
