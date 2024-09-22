<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product;

use Modules\Product\Domain\events\Deleted;
use Modules\Product\Exceptions\ProductDeletedException;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    public function test_success(): void
    {
        $product = (new ProductBuilder())->build();

        $this->assertFalse($product->isDeleted());

        $product->delete();

        $this->assertInstanceOf(\DateTimeImmutable::class, $product->isDeleted());

        $this->assertNotEmpty($events = $product->events()->release());
        /** @var Deleted $event */
        $event = end($events);

        $this->assertEquals($product->getUuid(), $event->getUuid());
        $this->assertInstanceOf(Deleted::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());

        $json = json_encode([
            'uuid'       => $product->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    public function test_already_published_exception(): void
    {
        $product = (new ProductBuilder())->deleted()->build();

        $this->expectException(ProductDeletedException::class);
        $product->delete();
    }
}
