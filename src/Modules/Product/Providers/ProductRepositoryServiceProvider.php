<?php

declare(strict_types=1);

namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Product\Infrastructure\DBRepository;
use Modules\Product\Infrastructure\Repository;

final class ProductRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Repository::class, DBRepository::class);
    }
}
