<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use Modules\Company\Domain\Company;
use Modules\Company\Infrastructure\MemoryRepository;
use Modules\Company\Infrastructure\Repository;
use Tests\TestCase;

abstract class CompanyTest extends TestCase
{
    protected Repository $repository;

    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, MemoryRepository::class);

        $this->repository = app()->make(Repository::class);
    }

}
