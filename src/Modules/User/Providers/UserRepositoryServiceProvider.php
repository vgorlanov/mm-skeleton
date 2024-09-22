<?php

declare(strict_types=1);

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Infrastructure\DBRepository;
use Modules\User\Infrastructure\Repository;

final class UserRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Repository::class, DBRepository::class);
    }
}
