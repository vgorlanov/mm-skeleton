<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use Modules\Company\Infrastructure\MemoryRepository;
use Modules\Company\Infrastructure\Repository;

final class CreateTest extends ProductTest
{
    private const ROUTE = 'admin.product.create';

    public function test_success(): void
    {
        $this->postJson($this->url(self::ROUTE), $this->request)->assertStatus(201);
    }

    /*
    |--------------------------------------------------------------------------
    | Company validation
    |--------------------------------------------------------------------------
    */
    public function test_company_empty_validation(): void
    {
        unset($this->request['company']);
        $this->postJson($this->url(self::ROUTE), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['title', 'body', 'params', 'images'])
            ->assertJsonValidationErrors(['company']);
    }

    public function test_company_invalid_format_validation(): void
    {
        $this->request['company'] = 'invalid_uuid';
        $this->postJson($this->url(self::ROUTE), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['title', 'body', 'params', 'images'])
            ->assertJsonValidationErrors(['company']);
    }

    public function test_company_notExists_validation(): void
    {
        $this->app->singleton(Repository::class, MemoryRepository::class);
        $this->postJson($this->url(self::ROUTE), $this->request)
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
        $this->postJson($this->url(self::ROUTE), $this->request)
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
        $this->postJson($this->url(self::ROUTE), $this->request)
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['company', 'title', 'params', 'images'])
            ->assertJsonValidationErrors(['body']);
    }

}
