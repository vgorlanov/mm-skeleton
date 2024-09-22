<?php

declare(strict_types=1);

namespace Modules\Company\Infrastructure;

use Common\Uuid\Uuid;
use Modules\Company\Domain\Company;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyExistsException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

final class MemoryRepository implements Repository
{
    /**
     * @var Company[]
     */
    private array $items = [];

    public function get(Uuid $uuid): Company
    {
        if (array_key_exists($uuid->toString(), $this->items)) {
            return clone $this->items[$uuid->toString()];
        }

        throw new RepositoryCompanyNotFoundException($uuid);
    }

    public function add(Company $company): void
    {
        if (array_key_exists($company->getUuid()->toString(), $this->items)) {
            throw new RepositoryCompanyExistsException($company->getUuid());
        }
        $this->items[$company->getUuid()->toString()] = $company;
    }

    public function update(Company $company): void
    {
        $uuid = $company->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            $this->items[$uuid] = $company;
        } else {
            throw new RepositoryCompanyNotFoundException($company->getUuid());
        }
    }

    public function remove(Company $company): void
    {
        $uuid = $company->getUuid()->toString();
        if (array_key_exists($uuid, $this->items)) {
            unset($this->items[$uuid]);
        } else {
            throw new RepositoryCompanyNotFoundException($company->getUuid());
        }
    }

    public function hasAlias(string $alias): bool
    {
        foreach ($this->items as $item) {
            if($item->getAbout()->alias === $alias) {
                return true;
            }
        }

        return false;
    }
}
