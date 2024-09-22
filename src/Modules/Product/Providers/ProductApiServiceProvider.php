<?php

declare(strict_types=1);

namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Product\API\Create\Create;
use Modules\Product\API\Create\Module as CreateModule;
use Modules\Product\API\Delete\Delete;
use Modules\Product\API\Delete\Module as DeleteModule;
use Modules\Product\API\GetById\GetById;
use Modules\Product\API\GetById\Module as GetByIdModule;
use Modules\Product\API\Publish\Module as PublishModule;
use Modules\Product\API\Publish\Publish;
use Modules\Product\API\Restore\Module as RestoreModule;
use Modules\Product\API\Restore\Restore;
use Modules\Product\API\UnPublish\Module as UnPublishModule;
use Modules\Product\API\UnPublish\UnPublish;
use Modules\Product\API\Update\Module as UpdateModule;
use Modules\Product\API\Update\Update;

final class ProductApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Create::class, CreateModule::class);
        $this->app->bind(Publish::class, PublishModule::class);
        $this->app->bind(UnPublish::class, UnPublishModule::class);
        $this->app->bind(Delete::class, DeleteModule::class);
        $this->app->bind(Restore::class, RestoreModule::class);
        $this->app->bind(GetById::class, GetByIdModule::class);
        $this->app->bind(Update::class, UpdateModule::class);
    }
}
