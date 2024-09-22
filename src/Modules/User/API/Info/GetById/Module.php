<?php

declare(strict_types=1);


namespace Modules\User\API\Info\GetById;


use Common\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Modules\User\Domain\Status;

final readonly class Module implements GetById
{
    public function get(Uuid $uuid): array
    {
        $user = DB::table('user_users')->where('uuid', '=', $uuid->toString())->first();

        // todo exception

        $status = Status::from($user->status);

        return [
            'uuid' => $user->uuid,
            'credential' => json_decode($user->credential, false, 512, JSON_THROW_ON_ERROR),
            'data' => json_decode($user->data, false, 512, JSON_THROW_ON_ERROR),
            'status' => [
                'value'  => $status->value,
                'title' => $status->title()
            ],
        ];
    }
}
