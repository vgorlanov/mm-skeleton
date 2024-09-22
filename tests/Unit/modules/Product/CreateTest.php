<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\Product\Domain\Product;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    private \Faker\Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    /**
     * @throws \Exception
     */
    public function test_success(): void
    {
        $product = new Product(
            uuid: $uuid = Uuid::next(),
            company: $company = Uuid::next(),
            title: $title = $this->faker->text(50),
            body: $body = $this->faker->text,
            date: $data = new \DateTimeImmutable(),
            params: $params = $this->makeParams(),
            images: $images = $this->makeImages(),
        );

        $this->assertSame($uuid, $product->getUuid());
        $this->assertSame($company, $product->getCompany());
        $this->assertSame($title, $product->getTitle());
        $this->assertSame($body, $product->getBody());
        $this->assertSame($data, $product->getDate());
        $this->assertSame($params, $product->getParams());
        $this->assertSame($images, $product->getImages());
        $this->assertFalse($product->isPublished());
        $this->assertFalse($product->isDeleted());
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    private function makeParams(): array
    {
        $faker = Factory::create();
        $params = [];
        for ($i = 0; $i <= random_int(1, 5); $i++) {
            $params[$faker->word] = $faker->word;
        }

        return $params;
    }

    /**
     * @return array<string>
     * @throws \Exception
     */
    private function makeImages(): array
    {
        $images = [];
        for ($i = 0; $i <= random_int(1, 5); $i++) {
            $images[] = $this->faker->imageUrl;
        }
        return $images;
    }
}
