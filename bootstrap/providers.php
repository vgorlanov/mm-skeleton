<?php

return [
    // Common
    Common\Events\Providers\EventsDispatchServiceProvider::class,

    // User
    Modules\User\Providers\UserRepositoryServiceProvider::class,
    Modules\User\Providers\UserApiServiceProvider::class,

    // Company
    Modules\Company\Providers\CompanyRepositoryServiceProvider::class,
    Modules\Company\Providers\CompanyApiServiceProvider::class,

    // Product
    Modules\Product\Providers\ProductApiServiceProvider::class,
    Modules\Product\Providers\ProductRepositoryServiceProvider::class,

    // Order
    Modules\Order\Providers\OrderApiServiceProvider::class,
    Modules\Order\Providers\OrderRepositoryServiceProvider::class,
];
