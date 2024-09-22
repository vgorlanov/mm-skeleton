<?php

namespace Modules\Company\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Company\Infrastructure\DBRepository as DBCompanyRepository;
use Modules\Company\Infrastructure\Repository;

class CompanyRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Repository::class, DBCompanyRepository::class);
    }
}
