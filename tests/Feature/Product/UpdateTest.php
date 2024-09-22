<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use Modules\Company\Infrastructure\MemoryRepository;
use Modules\Company\Infrastructure\Repository;
use Tests\Unit\modules\Product\ProductBuilder;

final class UpdateTest extends ProductTest
{
    private const ROUTE = 'admin.product.update';

    public function test_success(): void
    {
        $product = (new ProductBuilder())->build();
        $this->repository->add($product);

        $this->putJson($this->url(self::ROUTE, $product), $this->request);

        $updated = $this->repository->get($product->getUuid());

        $this->assertTrue($updated->getTitle() === $this->request['title'] && $updated->getTitle() !== $product->getTitle());
        $this->assertTrue($updated->getBody() === $this->request['body'] && $updated->getBody() !== $product->getBody());
        $this->assertTrue($updated->getImages() === $this->request['images'] && $updated->getImages() !== $product->getImages());
        $this->assertTrue($updated->getParams() === $this->request['params'] && $updated->getParams() !== $product->getParams());
    }

    public function test_not_found(): void
    {
        $product = (new ProductBuilder())->build();

        $this->putJson($this->url(self::ROUTE, $product), $this->request)->assertStatus(404);
    }

    /*
    |--------------------------------------------------------------------------
    | Company validation
    |--------------------------------------------------------------------------
    */
    public function test_company_empty_validation(): void
    {
        unset($this->request['company']);
        $this->putJson($this->url(self::ROUTE, $this->product), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['title', 'body', 'params', 'images'])
            ->assertJsonValidationErrors(['company']);
    }

    public function test_company_invalid_format_validation(): void
    {
        $this->request['company'] = 'invalid_uuid';
        $this->putJson($this->url(self::ROUTE, $this->product), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['title', 'body', 'params', 'images'])
            ->assertJsonValidationErrors(['company']);
    }

    public function test_company_notExists_validation(): void
    {
        $this->app->singleton(Repository::class, MemoryRepository::class);
        $this->putJson($this->url(self::ROUTE, $this->product), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['title', 'body', 'params', 'images'])
            ->assertJsonValidationErrors(['company']);
    }

    /*
    |--------------------------------------------------------------------------
    | Title validation
    |--------------------------------------------------------------------------
    */
    public function test_title_empty_validation(): void
    {
        unset($this->request['title']);
        $this->putJson($this->url(self::ROUTE, $this->product), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['company', 'body', 'params', 'images'])
            ->assertJsonValidationErrors(['title']);
    }

    /*
    |--------------------------------------------------------------------------
    | Body validation
    |--------------------------------------------------------------------------
    */
    public function test_body_empty_validation(): void
    {
        unset($this->request['body']);
        $this->putJson($this->url(self::ROUTE, $this->product), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['company', 'title', 'params', 'images'])
            ->assertJsonValidationErrors(['body']);
    }

}
