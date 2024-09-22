<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product;

use Modules\Product\Domain\events\UnPublished;
use Modules\Product\Exceptions\ProductPublishedException;
use Tests\TestCase;

final class UnPublishTest extends TestCase
{
    public function test_success(): void
    {
        $product = (new ProductBuilder())->published()->build();

        $this->assertTrue($product->isPublished());

        $product->unPublish();

        $this->assertFalse($product->isPublished());

        $this->assertNotEmpty($events = $product->events()->release());
        /** @var UnPublished $event */
        $event = end($events);

        $this->assertEquals($product->getUuid(), $event->getUuid());
        $this->assertInstanceOf(UnPublished::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());

        $json = json_encode([
            'uuid'       => $product->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    public function test_already_published_exception(): void
    {
        $product = (new ProductBuilder())->build();

        $this->expectException(ProductPublishedException::class);
        $product->unPublish();
    }
}
