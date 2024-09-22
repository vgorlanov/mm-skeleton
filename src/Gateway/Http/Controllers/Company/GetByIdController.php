<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Company;

use Common\Uuid\Uuid;
use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
//use Modules\Search\API\GetById\GetById;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(Controller::PREFIX)]
final class GetByIdController extends Controller
{
    private const string URI = '/company/{uuid}';

    private const string INDEX = 'company';

    public function __construct(
        //        private readonly GetById $get,
    ) {}

    #[Get(uri: self::URI, name: 'company.show')]
    #[OAT\Get(
        path: self::PREFIX . self::URI,
        summary: 'Компания',
        tags: ['Company'],
        parameters: [
            new OAT\Parameter(
                name: 'uuid',
                description: 'ID компании',
                in: 'path',
                required: true,
                content: new OAT\JsonContent(type: 'string'),
            ),
        ],
        responses: [
            new Response(response: 200, description: 'Success'),
            new Response(response: 404, description: 'Company not found'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            return response()->json();
            //            return response()->json($this->get->get(self::INDEX, new Uuid($uuid)));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}
