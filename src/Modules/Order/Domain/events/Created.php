<?php

declare(strict_types=1);

namespace Modules\Order\Domain\events;

use Common\Events\DomainEvent;
use Common\Uuid\Uuid;
use Modules\Order\Domain\Customer;

final class Created extends DomainEvent
{
    private \DateTimeImmutable $occurred;

    public function __construct(
        private readonly Uuid $uuid,
        private readonly Uuid $company,
        private readonly Customer $customer,
        //        private readonly ?array $products = null,
    ) {
        parent::__construct();

        $this->occurred = new \DateTimeImmutable();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurred;
    }

    public function toJson(): string
    {
        return json_encode([
            'uuid' => $this->uuid->toString(),
            'company' => $this->company->toString(),
            //            'customer' => $this->customer->toString(),
            //            'products' => $this->products, todo продукты в json
        ], JSON_THROW_ON_ERROR);
    }
}
