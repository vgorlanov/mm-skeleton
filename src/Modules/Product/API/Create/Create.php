<?php

declare(strict_types=1);

namespace Modules\Product\API\Create;

use Illuminate\Http\Request;

interface Create
{
    public function create(Request $request): true;
}
