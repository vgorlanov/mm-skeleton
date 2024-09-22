<?php

namespace Gateway\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\Server;
use OpenApi\Attributes\Tag;

#[Info(
    version: 'v1',
    title: 'Promo Place Project',
)]
#[Server(url: 'http://0.0.0.0:8181/api/', description: '(dev)')] // todo поставить нормальный url
#[Tag(name: 'Company')]
#[Tag(name: 'Company (admin)')]
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public const PREFIX = '/v1';
}
