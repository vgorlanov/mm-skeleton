<?php

declare(strict_types=1);

namespace Common\Queue\Events;

use Common\Queue\Attributes\Event;
use Common\Queue\Attributes\Exchange;
use Common\Queue\Attributes\Key;
use Common\Queue\Exceptions\QueueEventsException;
use Common\Queue\RabbitMQ\Exchanges\DomainEvents;
use Common\Queue\RabbitMQ\Keys;
use Modules\Company\Domain\events\AboutUpdated;
use Modules\Company\Domain\events\Activated;
use Modules\Company\Domain\events\Deleted;
use Modules\Company\Domain\events\InformationUpdated;
use Modules\Product\Domain\events\Published;
use Modules\Product\Domain\events\Updated;

enum Events: string
{
    use GetsAttributes;

    #[Key(Keys::COMPANY_SAVE)]
    #[Exchange(new DomainEvents())]
    #[Event(AboutUpdated::class)]
    case COMPANY_ABOUT_UPDATE = 'company.about.update';

    #[Key(Keys::COMPANY_SAVE)]
    #[Exchange(new DomainEvents())]
    #[Event(InformationUpdated::class)]
    case COMPANY_INFORMATION_UPDATE = 'company.information.update';

    #[Key(Keys::COMPANY_SAVE)]
    #[Exchange(new DomainEvents())]
    #[Event(Activated::class)]
    case COMPANY_ACTIVATED = 'company.activated';

    #[Key(Keys::COMPANY_DELETE)]
    #[Exchange(new DomainEvents())]
    #[Event(Deleted::class)]
    case COMPANY_DELETED = 'company.deleted';

    #[Key(Keys::PRODUCT_SAVE)]
    #[Exchange(new DomainEvents())]
    #[Event(Published::class)]
    case PRODUCT_PUBLISHED = 'product.published';

    #[Key(Keys::PRODUCT_SAVE)]
    #[Exchange(new DomainEvents())]
    #[Event(Updated::class)]
    case PRODUCT_UPDATED = 'product.updated';

    /**
     * @param class-string<\Common\Queue\Queueable> $event
     * @return Events
     * @throws QueueEventsException
     */
    public static function forEvent(string $event): Events
    {
        foreach (Events::cases() as $ev) {
            if ($ev->event() === $event) {
                return $ev;
            }
        }

        throw QueueEventsException::EventNotDefined();
    }
}
