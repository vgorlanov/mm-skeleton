<?php

declare(strict_types=1);


namespace Modules\Order\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Order\Infrastructure\DBRepository;
use Modules\Order\Infrastructure\Repository;

final class OrderRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Repository::class, DBRepository::class);
    }
}
