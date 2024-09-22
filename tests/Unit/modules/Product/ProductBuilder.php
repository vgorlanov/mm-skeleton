<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product;

use Common\Uuid\Uuid;
use DateTimeImmutable;
use Faker\Factory;
use Modules\Product\Domain\Product;

final class ProductBuilder
{
    private Uuid $uuid;
    private Uuid $company;
    private string $title;
    private string $body;
    /**
     * @var array<string, string>
     */
    private array $params;

    private DateTimeImmutable $date;

    /**
     * @var array<string>
     */
    private array $images;

    private bool $published = false;

    private ?DateTimeImmutable $deleted = null;

    private \Faker\Generator $faker;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->faker = Factory::create();

        $this->uuid = Uuid::next();
        $this->company = Uuid::next();
        $this->title = $this->faker->text(50);
        $this->body = $this->faker->text;
        $this->params = $this->makeParams();
        $this->images = $this->makeImages();
        $this->date = new DateTimeImmutable();
    }

    public function withId(Uuid $uuid): self
    {
        $clone = clone $this;
        $clone->uuid = $uuid;
        return $clone;
    }

    public function withCompany(Uuid $company): self
    {
        $clone = clone $this;
        $clone->company = $company;
        return $clone;
    }

    public function withTitle(string $title): self
    {
        $clone = clone $this;
        $clone->title = $title;
        return $clone;
    }

    public function withBody(string $body): self
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    /**
     * @param array<string, string> $params
     * @return $this
     */
    public function withParams(array $params): self
    {
        $clone = clone $this;
        $clone->params = $params;
        return $clone;
    }

    public function published(): self
    {
        $clone = clone $this;
        $clone->published = true;
        return $clone;
    }

    public function deleted(DateTimeImmutable $date = null): self
    {
        $clone = clone $this;
        $clone->deleted = $date ?: new DateTimeImmutable();
        return $clone;
    }

    /**
     * @param array<string> $images
     * @return self
     */
    public function withImages(array $images): self
    {
        $clone = clone $this;
        $clone->images = $images;
        return $clone;
    }

    /**
     * @return Product
     */
    public function build(): Product
    {
        $product = new Product(
            uuid: $this->uuid,
            company: $this->company,
            title: $this->title,
            body: $this->body,
            date: $this->date,
            params: $this->params,
            images: $this->images,
        );

        if ($this->published) {
            $product->publish();
        }

        if ($this->deleted instanceof DateTimeImmutable) {
            $product->delete($this->date);
        }

        return $product;
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    private function makeParams(): array
    {
        $params = [];
        for ($i = 0; $i <= random_int(1, 5); $i++) {
            $params[$this->faker->word] = $this->faker->word;
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
