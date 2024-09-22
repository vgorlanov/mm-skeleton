<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure;

use Common\Hydrator\Hydrator;
use Common\Status\Status as CommonStatus;
use Common\Uuid\Uuid;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\User\Domain\Credential;
use Modules\User\Domain\Data;
use Modules\User\Domain\Gender;
use Modules\User\Domain\Status;
use Modules\User\Domain\User;

/**
 * @phpstan-import-type CollectionStatuses from CommonStatus
 * @phpstan-type DBUserT object{uuid: string, credential: string, data: string, statuses: string, status: string, date: string}
 */
final class DBRepository implements Repository
{
    private const TABLE = 'user_users';

    private Hydrator $hydrator;

    public function __construct()
    {
        $this->hydrator = new Hydrator();
    }

    /**
     * @param Uuid $uuid
     * @return User
     * @throws RepositoryUserNotFoundException
     * @throws \ReflectionException
     */
    public function get(Uuid $uuid): User
    {
        /** @var DBUserT|null $result */
        $result = $this->builder()->where('uuid', $uuid->toString())->first();

        if ($result === null) {
            throw new RepositoryUserNotFoundException($uuid->toString());
        }

        return $this->hydrate($uuid, $result);
    }

    /**
     * @param User $user
     * @return void
     * @throws RepositoryUserAlreadyExistsException
     * @throws \JsonException
     */
    public function add(User $user): void
    {
        $uuid = $user->getUuid()->toString();
        $exists = $this->builder()->where('uuid', '=', $uuid)->first();

        if ($exists) {
            throw new RepositoryUserAlreadyExistsException($uuid);
        }

        $this->builder()->insert($this->userToArray($user));
    }

    public function update(User $user): void
    {
        $this->get($user->getUuid());

        $this->builder()
            ->where('uuid', '=', $user->getUuid()->toString())
            ->update($this->userToArray($user));
    }

    public function remove(User $user): void
    {
        $this->get($user->getUuid());

        $this->builder()
            ->where('uuid', '=', $user->getUuid()->toString())
            ->delete();
    }

    public function getByEmail(string $email): User
    {
        /** @var DBUserT|null $result */
        $result = $this->builder()->where('credential->email', $email)->first();

        if ($result === null) {
            throw new RepositoryUserNotFoundException($email);
        }

        return $this->hydrate(new Uuid($result->uuid), $result);
    }

    /**
     * @param Uuid $uuid
     * @param DBUserT $result
     * @return User
     * @throws \ReflectionException
     */
    private function hydrate(Uuid $uuid, object $result): User
    {
        $data = json_decode($result->data, true);
        $data['gender'] = Gender::from($data['gender']);
        $data['birthday'] = $data['birthday'] ? new \DateTimeImmutable($data['birthday']) : null;

        return $this->hydrator->hydrate(User::class, [
            'uuid'       => $uuid,
            'credential' => new Credential(...json_decode($result->credential, true)),
            'data'       => new Data(...$data),
            'statuses'   => $this->statuses(json_decode($result->statuses)),
            'date'       => new \DateTimeImmutable($result->date),
        ]);
    }

    private function builder(): Builder
    {
        return DB::table(self::TABLE);
    }

    /**
     * @param array<mixed> $statuses
     * @return array<CollectionStatuses>
     * @throws \Exception
     */
    private function statuses(array $statuses): array
    {
        return array_map(fn($item): array => [
            'date'   => new \DateTimeImmutable($item->date->date),
            'status' => Status::from($item->status),
        ], $statuses);
    }

    /**
     * @param User $user
     * @return array<mixed>
     * @throws \JsonException
     */
    private function userToArray(User $user): array
    {
        return [
            'uuid'       => $user->getUuid()->toString(),
            'credential' => $user->getCredential()->toJSON(),
            'data'       => $user->getData()->toJSON(),
            'statuses'   => json_encode($user->status()->list()),
            'status'     => $user->status()->current()->value, // @phpstan-ignore-line
            'date'       => $user->getDate()->format('Y-m-d H:i:s.u'),
        ];
    }
}
