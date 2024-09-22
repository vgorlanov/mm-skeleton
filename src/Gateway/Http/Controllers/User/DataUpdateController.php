<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\User;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\User\API\System\DataUpdate\DataUpdate;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix(Controller::PREFIX)]
final class DataUpdateController extends Controller
{
    private const URI = '/user/{uuid}/data/update';

    public function __construct(
        private readonly DataUpdate $update,
    ) {}

    #[Put(uri: self::URI, name: 'user.data.update')]
    #[OAT\Put(
        path: self::PREFIX . self::URI,
        summary: 'Обновление данных пользователя',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/DataDto',
            ),
        ),
        tags: ['User'],
        parameters: [
            new OAT\Parameter(
                name: 'uuid',
                description: 'ID пользователя',
                in: 'path',
                required: true,
                content: new OAT\JsonContent(type: 'string'),
            ),
        ],
        responses: [
            new Response(response: 200, description: 'Data updated'),
            new Response(response: 404, description: 'User not found'),
        ],
    )]
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {
            $this->update->update(new Uuid($uuid), $request);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('Data updated');
    }


}
