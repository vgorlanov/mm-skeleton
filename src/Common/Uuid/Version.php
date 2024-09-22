<?php

namespace Common\Uuid;

use Ramsey\Uuid\UuidInterface;

enum Version: string
{
    // todo доделать остальные версии

    case V1 = 'v1';
    //    case V2 = 'v2';
    //    case V3 = 'v3';
    case V4 = 'v4';
    //    case V5 = 'v5';
    case V6 = 'v6';
    case V7 = 'v7';

    public function make(): UuidInterface
    {
        return match ($this) {
            Version::V1 => \Ramsey\Uuid\Uuid::uuid1(),
            //            Version::V2 => \Ramsey\Uuid\Uuid::uuid2(),
            //            Version::V3 => \Ramsey\Uuid\Uuid::uuid3(),
            Version::V4 => \Ramsey\Uuid\Uuid::uuid4(),
            //            Version::V5 => \Ramsey\Uuid\Uuid::uuid5(),
            Version::V6 => \Ramsey\Uuid\Uuid::uuid6(),
            Version::V7 => \Ramsey\Uuid\Uuid::uuid7(),
        };
    }
}
