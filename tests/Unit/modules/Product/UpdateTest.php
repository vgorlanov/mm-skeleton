<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product;

use Modules\Product\Domain\events\Updated;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    public function test_success(): void
    {
        $product = (new ProductBuilder())->build();
        $new = (new ProductBuilder())->build();

        $product->update(
            title: $new->getTitle(),
            body: $new->getBody(),
            params: $new->getParams(),
            images: $new->getImages(),
        );

        $this->assertNotEmpty($events = $product->events()->release());
        /** @var Updated $event */
        $event = end($events);

        $this->assertEquals($product->getUuid(), $event->getUuid());
        $this->assertInstanceOf(Updated::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertTrue($product->getTitle() === $event->getTitle() && $product->getTitle() === $new->getTitle());
        $this->assertTrue($product->getBody() === $event->getBody() && $product->getBody() === $new->getBody());
        $this->assertTrue($product->getImages() === $event->getImages() && $product->getImages() === $new->getImages());
        $this->assertTrue($product->getParams() === $event->getParams() && $product->getParams() === $new->getParams());

        $json = json_encode([
            'uuid'       => $product->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
            'title'      => $event->getTitle(),
            'body'       => $event->getBody(),
            'params'     => $event->getParams(),
            'images'     => $event->getImages(),
        ]);

        $this->assertSame($json, $event->toJson());
    }
}
