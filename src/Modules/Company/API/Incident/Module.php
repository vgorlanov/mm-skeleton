<?php

declare(strict_types=1);

namespace Modules\Company\API\Incident;

use Illuminate\Http\Request;
use Modules\Company\Incident\Incident;

final class Module implements Create
{
    public function create(Request $request): true
    {
        // todo validation
        Incident::create([
            'number' => $request->get('n'),
        ]);
    }
}
