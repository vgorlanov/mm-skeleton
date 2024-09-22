<?php

declare(strict_types=1);

namespace Modules\Order\Infrastructure;

use Common\Hydrator\Hydrator;
use Common\Status\Status as CommonStatus;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Order\Domain\Status;
use Modules\Order\Domain\Customer;
use Modules\Order\Domain\Order;
use Modules\Order\Domain\Price;
use Modules\Order\Domain\Product;
use Modules\Order\Exceptions\RepositoryOrderExistsException;
use Modules\Order\Exceptions\RepositoryOrderNotFoundException;

/**
 * @phpstan-import-type OrderProductData from Product
 * @phpstan-import-type CollectionStatuses from CommonStatus
 * @phpstan-type DBPrice object{main: int, fractional: int, negative: bool}
 * @phpstan-type DBProduct object{uuid: non-empty-string, name: non-empty-string, price: DBPrice, quantity: non-negative-int}
 * @phpstan-type DBOrder object{uuid: non-empty-string, company: non-empty-string, customer: non-empty-string,
 *     products: non-empty-string, statuses: non-empty-string, status: non-empty-string, date: non-empty-string,
 *     name: non-empty-string, email: non-empty-string}
 */
final class DBRepository implements Repository
{
    private const string CUSTOMER_TABLE = 'order_customers';
    private const string TABLE = 'order_orders';

    private Hydrator $hydrator;

    public function __construct()
    {
        $this->hydrator = new Hydrator();
    }

    /**
     * @param Uuid $uuid
     * @return Order
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws Exception
     */
    public function get(Uuid $uuid): Order
    {
        /** @var DBOrder|null $result */
        $result = $this
            ->builder()
            ->select(self::TABLE . '.*', self::CUSTOMER_TABLE . '.name', self::CUSTOMER_TABLE . '.email')
            ->leftJoin(self::CUSTOMER_TABLE, self::TABLE . '.customer', '=', self::CUSTOMER_TABLE . '.uuid')
            ->where(self::TABLE . '.uuid', '=', $uuid->toString())
            ->first();

        if ($result === null) {
            throw new RepositoryOrderNotFoundException($uuid);
        }

        return $this->hydrator->hydrate(Order::class, [
            'uuid' => $uuid,
            'company' => new Uuid($result->company),
            'customer' => new Customer(
                uuid: new Uuid($result->customer),
                name: $result->name,
                email: $result->email,
            ),
            'products' => $this->makeProducts(json_decode($result->products, false, 512, JSON_THROW_ON_ERROR)),
            'statuses' => $this->statuses(json_decode($result->statuses, false, 512, JSON_THROW_ON_ERROR)),
            'date' => new DateTimeImmutable($result->date),
        ]);
    }

    /**
     * @param Order $order
     * @return void
     * @throws \JsonException
     */
    public function add(Order $order): void
    {
        if ($this->exists($order)) {
            throw new RepositoryOrderExistsException($order->getUuid());
        }

        DB::beginTransaction();
        $this->customer($order->getCustomer());
        $this->builder()->insert($this->orderToArray($order));
        DB::commit();
    }

    /**
     * @throws \JsonException
     */
    public function update(Order $order): void
    {
        if (!$this->exists($order)) {
            throw new RepositoryOrderNotFoundException($order->getUuid());
        }

        $this->builder()
            ->where('uuid', '=', $order->getUuid()->toString())
            ->update($this->orderToArray($order));
    }

    public function remove(Order $order): void
    {
        if (!$this->exists($order)) {
            throw new RepositoryOrderNotFoundException($order->getUuid());
        }

        $this->builder()
            ->where('uuid', '=', $order->getUuid()->toString())
            ->delete();
    }

    /**
     * @param array<mixed> $statuses
     * @return array<CollectionStatuses>
     * @throws Exception
     */
    private function statuses(array $statuses): array
    {
        return array_map(static fn($item): array => [
            'date' => new DateTimeImmutable($item->date->date),
            'status' => Status::from($item->status),
        ], $statuses);
    }

    /**
     * @param array<DBProduct> $products
     * @return Product[]
     */
    private function makeProducts(array $products): array
    {
        return array_map(
            static fn(object $item) => new Product(
                uuid: new Uuid($item->uuid),
                name: $item->name,
                price: new Price(
                    main: $item->price->main,
                    fractional: $item->price->fractional,
                    negative: $item->price->negative,
                ),
                quantity: $item->quantity,
            ),
            $products,
        );
    }

    /**
     * @param Product[] $products
     * @return array<OrderProductData>
     */
    private function productsToArray(array $products): array
    {
        return array_map(static fn($product) => $product->toArray(), $products);
    }

    private function exists(Order $order): bool
    {
        $exists = $this->builder()->where('uuid', '=', $order->getUuid()->toString())->first();

        return !($exists === null);
    }

    /**
     * @param Order $order
     * @return array<mixed>
     * @throws \JsonException
     */
    private function orderToArray(Order $order): array
    {
        return [
            'uuid' => $order->getUuid()->toString(),
            'company' => $order->getCompany()->toString(),
            'customer' => $order->getCustomer()->uuid->toString(),
            'products' => json_encode($this->productsToArray($order->getProducts()), JSON_THROW_ON_ERROR),
            'statuses' => json_encode($order->status()->list(), JSON_THROW_ON_ERROR),
            'status' => $order->status()->current()->value,
            'date' => $order->getDate()->format('Y-m-d H:i:s.u'),
        ];
    }

    private function builder(): Builder
    {
        return DB::table(self::TABLE);
    }

    private function customer(Customer $customer): void
    {
        DB::table(self::CUSTOMER_TABLE)->updateOrInsert(
            ['uuid' => $customer->uuid->toString()],
            ['name' => $customer->name, 'email' => $customer->email],
        );
    }
}
