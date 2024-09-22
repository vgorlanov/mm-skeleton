<?php

declare(strict_types=1);

namespace Tests\Unit\common\Status;

use Common\Status\Exceptions\TitleNotFoundException;
use Tests\TestCase;

final class TitleTest extends TestCase
{
    public function test_exists_success(): void
    {
        $this->assertIsString(Statuses::EXISTS->title());
    }

    public function test_not_exists_exception(): void
    {
        $this->expectException(TitleNotFoundException::class);
        Statuses::NOT->title();
    }
}
