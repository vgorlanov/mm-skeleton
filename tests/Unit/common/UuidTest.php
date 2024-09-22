<?php

declare(strict_types=1);

namespace Tests\Unit\common;

use Common\Uuid\Uuid;
use Common\Uuid\Version;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class UuidTest extends TestCase
{
    #[DataProvider('versionsProvider')]
    public function test_make(Version $version, int $result): void
    {
        $uuid = Uuid::next($version);

        $from = \Ramsey\Uuid\Uuid::fromString($uuid->toString());

        $this->assertSame($from->getFields()->getVersion(), $result); //@phpstan-ignore-line
    }

    /**
     * @return array<array{Version, int}>
     */
    public static function versionsProvider(): array
    {
        return [
            [Version::V1, 1],
            [Version::V4, 4],
            [Version::V6, 6],
            [Version::V7, 7],
        ];
    }
}
