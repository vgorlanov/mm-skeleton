<?php

declare(strict_types=1);

namespace Modules\Company\Domain;

use Common\EntityAggregate;
use Common\Events\DomainEventPublisher;
use Common\Status\HasStatus;
use Common\Status\Status as CommonStatus;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\Company\Domain\events\AboutUpdated;
use Modules\Company\Domain\events\Activated;
use Modules\Company\Domain\events\Blocked;
use Modules\Company\Domain\events\ContactsUpdated;
use Modules\Company\Domain\events\Created;
use Modules\Company\Domain\events\Deleted;
use Modules\Company\Domain\events\InformationUpdated;
use Modules\Company\Domain\events\Restored;
use Modules\Company\Domain\events\UnBlocked;
use Modules\Company\Exceptions\CompanyAlreadyActivatedException;
use Modules\Company\Exceptions\CompanyBlockedException;
use Modules\Company\Exceptions\CompanyDeletedException;

/**
 * @phpstan-import-type CollectionStatuses from CommonStatus
 * @immutable
 */
final class Company implements EntityAggregate, HasStatus
{
    /** @var array<CollectionStatuses> */
    private array $statuses = [];

    /**
     * @param Uuid $uuid
     * @param About $about
     * @param Contacts $contacts
     * @param Information $information
     * @param DateTimeImmutable $date
     */
    public function __construct(
        private readonly Uuid $uuid,
        private About $about,
        private Contacts $contacts,
        private Information $information,
        private readonly DateTimeImmutable $date,
    ) {
        $this->statuses = $this->status()->add(Status::NEW, new DateTimeImmutable());

        $this->events()->publish(new Created($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws CompanyAlreadyActivatedException
     */
    public function activate(DateTimeImmutable $date): void
    {
        if ($this->status()->current() === Status::ACTIVE) {
            throw new CompanyAlreadyActivatedException();
        }

        $this->status()->add(Status::ACTIVE, $date);
        $this->events()->publish(new Activated($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws CompanyBlockedException
     */
    public function block(DateTimeImmutable $date): void
    {
        if ($this->status()->current() === Status::BLOCK) {
            CompanyBlockedException::already();
        }

        $this->status()->add(Status::BLOCK, $date);
        $this->events()->publish(new Blocked($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws CompanyBlockedException
     */
    public function unblock(DateTimeImmutable $date): void
    {
        if ($this->status()->current() !== Status::BLOCK) {
            CompanyBlockedException::not();
        }

        $statuses = $this->status()->list();
        $last = $statuses[count($statuses) - 2];

        $this->status()->add($last['status'], $date);
        $this->events()->publish(new UnBlocked($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws CompanyDeletedException
     */
    public function delete(DateTimeImmutable $date): void
    {
        if ($this->status()->current() === Status::DELETE) {
            CompanyDeletedException::already();
        }

        $this->status()->add(Status::DELETE, $date);
        $this->events()->publish(new Deleted($this->uuid));
    }

    /**
     * @param DateTimeImmutable $date
     * @return void
     * @throws CompanyDeletedException
     */
    public function restore(DateTimeImmutable $date): void
    {
        if ($this->status()->current() !== Status::DELETE) {
            CompanyDeletedException::not();
        }

        $statuses = $this->status()->list();
        $last = $statuses[count($statuses) - 2];

        $this->status()->add($last['status'], $date);
        $this->events()->publish(new Restored($this->uuid));
    }

    public function changeAbout(About $about): void
    {
        $this->about = $about;
        $this->events()->publish(new AboutUpdated($this->uuid, $about));
    }

    public function changeContacts(Contacts $contacts): void
    {
        $this->contacts = $contacts;
        $this->events()->publish(new ContactsUpdated($this->uuid, $contacts));
    }

    public function changeInformation(Information $information): void
    {
        $this->information = $information;
        $this->events()->publish(new InformationUpdated($this->uuid, $information));
    }

    /**
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @return About
     */
    public function getAbout(): About
    {
        return $this->about;
    }

    /**
     * @return Contacts
     */
    public function getContacts(): Contacts
    {
        return $this->contacts;
    }

    /**
     * @return Information
     */
    public function getInformation(): Information
    {
        return $this->information;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function status(): CommonStatus
    {
        return new CommonStatus($this->statuses);
    }

    public function events(): DomainEventPublisher
    {
        return (DomainEventPublisher::instance())->setEntity($this);
    }
}
