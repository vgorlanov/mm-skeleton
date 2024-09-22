<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Company\Admin;

use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Company\API\Create\Create;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class CreateController extends AdminController
{
    private const URI = '/company/create';

    public function __construct(
        private readonly Create $api,
    ) {}

    #[Post(uri: self::URI, name: 'admin.company.create')]
    #[OAT\Post(
        path: self::PREFIX . self::URI,
        summary: 'Создание новой компании',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/CompanyDto',
            ),
        ),
        tags: ['Company (admin)'],
        responses: [
            new Response(response: 201, description: 'Created'),
            new Response(response: 422, description: 'Validation'),
        ],
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $this->api->create($request);

        return response()->json('Company created', 201);
    }
}
