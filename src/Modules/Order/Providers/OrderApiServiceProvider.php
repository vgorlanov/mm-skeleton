<?php

declare(strict_types=1);


namespace Modules\Order\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Order\API\System\Create\{Create as SystemCreate, Module as SystemCreateModule};
use Modules\Order\API\System\Activate\{Activate as SystemActivate, Module as SystemActivateModule};
use Modules\Order\API\System\Cancel\{Cancel as SystemCancel, Module as  SystemCancelModule};
use Modules\Order\API\System\Complete\{Complete as SystemComplete, Module as SystemCompleteModule};
use Modules\Order\API\Info\GetList\{GetList as InfoGetList, Module as InfoGetListModule};

final class OrderApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SystemActivate::class, SystemActivateModule::class);
        $this->app->bind(SystemCreate::class, SystemCreateModule::class);
        $this->app->bind(SystemCancel::class,  SystemCancelModule::class);
        $this->app->bind(SystemComplete::class, SystemCompleteModule::class);
        $this->app->bind(InfoGetList::class, InfoGetListModule::class);
    }
}
