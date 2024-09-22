<?php

declare(strict_types=1);

namespace Tests\Unit\Asset;

use Common\Events\Listeners\Listener;

final class TestListenerSuccess implements Listener
{
    public function handle(object $event): void {}

    public function rollback(object $event): void {}
}
