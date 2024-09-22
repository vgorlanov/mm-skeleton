<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services\dto;


use Common\Uuid\Uuid;

final readonly class OrderDto
{
    /**
     * @param Uuid $company
     * @param CustomerDto $customer
     * @param ProductDto[] $products
     * @param Uuid|null $uuid
     */
    public function __construct(
        public Uuid $company,
        public CustomerDto $customer,
        public array $products,
        public ?Uuid $uuid = null,
    ) {}
}
