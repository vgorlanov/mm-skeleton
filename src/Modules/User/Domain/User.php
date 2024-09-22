<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use Common\EntityAggregate;
use Common\Events\DomainEventPublisher;
use Common\Status\HasStatus;
use Common\Status\Status as CommonStatus;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\User\Domain\events\Activated;
use Modules\User\Domain\events\Blocked;
use Modules\User\Domain\events\Created;
use Modules\User\Domain\events\CredentialChanged;
use Modules\User\Domain\events\DataChanged;
use Modules\User\Domain\events\Deleted;
use Modules\User\Domain\events\Restored;
use Modules\User\Domain\events\UnBlocked;
use Modules\User\Exceptions\UserAlreadyActivatedException;
use Modules\User\Exceptions\UserBlockedException;
use Modules\User\Exceptions\UserDeletedException;

/**
 * @phpstan-import-type CollectionStatuses from CommonStatus
 */
final class User implements EntityAggregate, HasStatus
{
    /** @var array<CollectionStatuses> */
    private array $statuses = [];

    /**
     * @param Uuid $uuid
     * @param Credential $credential
     * @param Data $data
     * @param DateTimeImmutable $date
     */
    public function __construct(
        private readonly Uuid $uuid,
        private Credential $credential,
        private Data $data,
        private readonly DateTimeImmutable $date,
    ) {
        $this->statuses = $this->status()->add(Status::NEW, new DateTimeImmutable());

        $this->events()->publish(new Created($this->uuid));
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getCredential(): Credential
    {
        return $this->credential;
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws UserAlreadyActivatedException
     */
    public function activate(DateTimeImmutable $date): void
    {
        if ($this->status()->current() === Status::ACTIVE) {
            throw new UserAlreadyActivatedException();
        }

        $this->status()->add(Status::ACTIVE, $date);
        $this->events()->publish(new Activated($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws UserBlockedException
     */
    public function block(DateTimeImmutable $date): void
    {
        if ($this->status()->current() === Status::BLOCK) {
            UserBlockedException::alreadyBlocked();
        }

        $this->status()->add(Status::BLOCK, $date);
        $this->events()->publish(new Blocked($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws UserBlockedException
     */
    public function unBlock(DateTimeImmutable $date): void
    {
        if ($this->status()->current() !== Status::BLOCK) {
            UserBlockedException::notBlocked();
        }

        $statuses = $this->status()->list();
        $last = $statuses[count($statuses) - 2];

        $this->status()->add($last['status'], $date);
        $this->events()->publish(new UnBlocked($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws UserDeletedException
     */
    public function delete(DateTimeImmutable $date): void
    {
        if ($this->status()->current() === Status::DELETE) {
            UserDeletedException::alreadyDeleted();
        }

        $this->status()->add(Status::DELETE, $date);
        $this->events()->publish(new Deleted($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws UserDeletedException
     */
    public function restore(DateTimeImmutable $date): void
    {
        if ($this->status()->current() !== Status::DELETE) {
            UserDeletedException::notDeleted();
        }

        $statuses = $this->status()->list();
        $last = $statuses[count($statuses) - 2];

        $this->status()->add($last['status'], $date);
        $this->events()->publish(new Restored($this->uuid));
    }

    public function changeData(Data $data): void
    {
        $this->data = $data;
        $this->events()->publish(new DataChanged($this->uuid, $data));
    }

    public function changeCredential(Credential $credential): void
    {
        $this->credential = $credential;
        $this->events()->publish(new CredentialChanged($this->uuid, $credential));
    }

    public function events(): DomainEventPublisher
    {
        return (DomainEventPublisher::instance())->setEntity($this);
    }

    public function getData(): Data
    {
        return $this->data;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function status(): CommonStatus
    {
        return new CommonStatus($this->statuses);
    }
}
