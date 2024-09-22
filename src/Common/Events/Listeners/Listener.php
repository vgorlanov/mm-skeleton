<?php

declare(strict_types=1);

namespace Common\Events\Listeners;

/**
 * @template T of object
 */
interface Listener
{
    /**
     * @param T $event
     * @return void
     */
    public function handle(object $event): void;

    /**
     * @param T $event
     * @return void
     */
    public function rollback(object $event): void;
}
