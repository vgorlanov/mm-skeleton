<?php

declare(strict_types=1);

namespace Modules\Company\Infrastructure;

use Common\Hydrator\Hydrator;
use Common\Status\Status as CommonStatus;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Company\Domain\About;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\Contacts;
use Modules\Company\Domain\Information;
use Modules\Company\Domain\Status;
use Modules\Company\Domain\Type;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyExistsException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;

/**
 * @phpstan-import-type CollectionStatuses from CommonStatus
 * @phpstan-type DBCompanyT object{uuid: string, name: string, about: string, contacts: string, information: string, statuses: string, status: string, date: string}
 */
final class DBRepository implements Repository
{
    private const string TABLE = 'company_companies';

    private Hydrator $hydrator;

    public function __construct()
    {
        $this->hydrator = new Hydrator();
    }

    /**
     * @param Uuid $uuid
     * @return Company
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function get(Uuid $uuid): Company
    {
        /** @var DBCompanyT|null $result */
        $result = $this->builder()->where('uuid', $uuid->toString())->first();

        if ($result === null) {
            throw new RepositoryCompanyNotFoundException($uuid);
        }

        return $this->hydrator->hydrate(Company::class, [
            'uuid'        => $uuid,
            'about'       => new About(...json_decode($result->about, true, 512, JSON_THROW_ON_ERROR)),
            'contacts'    => new Contacts(...json_decode($result->contacts, true, 512, JSON_THROW_ON_ERROR)),
            'information' => $this->information(json_decode($result->information, true, 512, JSON_THROW_ON_ERROR)),
            'statuses'    => $this->statuses(json_decode($result->statuses, false, 512, JSON_THROW_ON_ERROR)),
            'date'        => new DateTimeImmutable($result->date),
        ]);
    }

    /**
     * @param Company $company
     * @return void
     * @throws \JsonException
     */
    public function add(Company $company): void
    {
        $exists = $this->builder()->where('uuid', '=', $company->getUuid()->toString())->first();

        if ($exists) {
            throw new RepositoryCompanyExistsException($company->getUuid());
        }

        $this->builder()->insert($this->companyToArray($company));
    }

    public function update(Company $company): void
    {
        $this->get($company->getUuid());

        $this->builder()
            ->where('uuid', '=', $company->getUuid()->toString())
            ->update($this->companyToArray($company));
    }

    public function remove(Company $company): void
    {
        $this->get($company->getUuid());

        $this->builder()
            ->where('uuid', '=', $company->getUuid()->toString())
            ->delete();
    }

    public function hasAlias(string $alias): bool
    {
        return (bool) $this->builder()
            ->where('about->alias', '=', $alias)
            ->first();
    }

    private function builder(): Builder
    {
        return DB::table(self::TABLE);
    }

    /**
     * @param array<mixed> $statuses
     * @return array<CollectionStatuses>
     * @throws Exception
     */
    private function statuses(array $statuses): array
    {
        return array_map(static fn($item): array => [
            'date'   => new DateTimeImmutable($item->date->date),
            'status' => Status::from($item->status),
        ], $statuses);
    }

    /**
     * @param array<mixed> $information
     * @return Information
     */
    private function information(array $information): Information
    {
        $information['type'] = Type::from($information['type']);

        return new Information(...$information);
    }

    /**
     * @param Company $company
     * @return array<mixed>
     * @throws \JsonException
     */
    private function companyToArray(Company $company): array
    {
        return [
            'uuid'        => $company->getUuid()->toString(),
            'name'        => $company->getAbout()->name,
            'about'       => $company->getAbout()->toJSON(),
            'contacts'    => $company->getContacts()->toJSON(),
            'information' => $company->getInformation()->toJSON(),
            'statuses'    => json_encode($company->status()->list(), JSON_THROW_ON_ERROR),
            'status'      => $company->status()->current()->value, // @phpstan-ignore-line
            'date'        => $company->getDate()->format('Y-m-d H:i:s.u'),
        ];
    }
}
