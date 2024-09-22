<?php

declare(strict_types=1);


namespace Modules\Order\API\Info\GetList;


use Common\Uuid\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final class Module implements GetList
{
    private const string TABLE = 'order_orders';
    private const string CUSTOMER = 'order_customers';

    public function list(int $perPage = 10, int $page = 1, Uuid $company = null): JsonResponse
    {
        $builder = DB::table(self::TABLE);

        if($company !== null)  {
            $builder->where('company', '=', $company->toString());
        }

        $result = $builder
            ->select([self::TABLE.'.*', self::CUSTOMER.'.name', self::CUSTOMER.'.email'])
            ->leftJoin(self::CUSTOMER, self::CUSTOMER . '.uuid', '=', self::TABLE . '.customer')
            ->paginate(perPage: $perPage, page: $page);

        dd($result);

    }
}
