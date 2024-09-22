<?php

declare(strict_types=1);

namespace Modules\Product\Infrastructure;

use Common\Hydrator\Hydrator;
use Common\Uuid\Uuid;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Product\Domain\Product;

/**
 * @phpstan-type DBProductT object{uuid: string, company: string, title: string, body: string, params: string|null, images: string|null, published: bool, date: string, deleted_at: string|null}
 */
final class DBRepository implements Repository
{
    private Hydrator $hydrator;

    public function __construct()
    {
        $this->hydrator = new Hydrator();
    }

    /**
     * @throws \ReflectionException
     * @throws RepositoryProductNotFoundException
     * @throws \Exception
     */
    public function get(Uuid $uuid): Product
    {
        /** @var DBProductT|null $result */
        $result = $this->builder()->where('uuid', $uuid->toString())->first();

        if ($result === null) {
            throw new RepositoryProductNotFoundException($uuid->toString());
        }

        return $this->hydrator->hydrate(Product::class, [
            'uuid'      => $uuid,
            'company'   => new Uuid($result->company),
            'title'     => $result->title,
            'body'      => $result->body,
            'params'    => $result->params ? json_decode($result->params, true) : null,
            'images'    => $result->images ? json_decode($result->images, true) : null,
            'published' => (bool) $result->published,
            'date'      => new \DateTimeImmutable($result->date),
            'deleted'   => $result->deleted_at ? new \DateTimeImmutable($result->deleted_at) : null,
        ]);
    }

    public function add(Product $product): void
    {
        $uuid = $product->getUuid()->toString();
        $exists = $this->builder()->where('uuid', '=', $uuid)->first();

        if ($exists) {
            throw new RepositoryProductAlreadyExistsException($uuid);
        }

        $this->builder()->insert($this->productToArray($product));
    }

    public function update(Product $product): void
    {
        $this->get($product->getUuid());

        $this->builder()
            ->where('uuid', '=', $product->getUuid()->toString())
            ->update($this->productToArray($product));
    }

    public function remove(Product $product): void
    {
        $this->get($product->getUuid());

        $this->builder()->where('uuid', '=', $product->getUuid()->toString())->delete();
    }

    private function builder(): Builder
    {
        return DB::table('product_products');
    }

    /**
     * @param Product $product
     * @return array<mixed>
     */
    private function productToArray(Product $product): array
    {
        $values = [
            'uuid'      => $product->getUuid()->toString(),
            'company'   => $product->getCompany()->toString(),
            'title'     => $product->getTitle(),
            'body'      => $product->getBody(),
            'params'    => json_encode($product->getParams()),
            'images'    => json_encode($product->getImages()),
            'published' => $product->isPublished(),
            'date'      => $product->getDate()->format('Y-m-d H:i:s.u'),
        ];

        if ($product->isDeleted() instanceof \DateTimeImmutable) {
            $values['deleted_at'] = $product->isDeleted()->format('Y-m-d H:i:s.u');
        } else {
            $values['deleted_at'] = null;
        }

        return $values;
    }
}
