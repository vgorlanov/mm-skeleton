<?php

declare(strict_types=1);

namespace Modules\Company\Infrastructure;

use Common\Uuid\Uuid;
use Modules\Company\Domain\Company;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyExistsException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

interface Repository
{
    /**
     * @param Uuid $uuid
     * @return Company
     * @throws RepositoryCompanyNotFoundException
     */
    public function get(Uuid $uuid): Company;

    /**
     * @param Company $company
     * @throws RepositoryCompanyExistsException
     */
    public function add(Company $company): void;

    /**
     * @param Company $company
     * @throws RepositoryCompanyNotFoundException
     */
    public function update(Company $company): void;

    /**
     * @param Company $company
     * @throws RepositoryCompanyNotFoundException
     */
    public function remove(Company $company): void;

    /**
     * @param string $alias
     * @return bool
     */
    public function hasAlias(string $alias): bool;
}
