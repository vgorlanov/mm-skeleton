<?php

declare(strict_types=1);

namespace Modules\Company\API\Create;

use Illuminate\Http\Request;

interface Create
{
    public function create(Request $request): true;
}
