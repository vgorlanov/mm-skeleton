<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Create;

use Illuminate\Http\Request;

interface Create
{
    public function create(Request $request): true;
}
