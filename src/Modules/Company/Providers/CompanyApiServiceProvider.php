<?php

namespace Modules\Company\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Company\API\AboutUpdate\AboutUpdate;
use Modules\Company\API\AboutUpdate\Module as AboutUpdateModule;
use Modules\Company\API\Activate\Activate;
use Modules\Company\API\Activate\Module as ActivateModule;
use Modules\Company\API\Block\Block;
use Modules\Company\API\Block\Module as BlockModule;
use Modules\Company\API\ContactsUpdate\ContactsUpdate;
use Modules\Company\API\ContactsUpdate\Module as ContactsUpdateModule;
use Modules\Company\API\Create\Create;
use Modules\Company\API\Create\Module as CreateModule;
use Modules\Company\API\Delete\Delete;
use Modules\Company\API\Delete\Module as DeleteModule;
use Modules\Company\API\End\End;
use Modules\Company\API\End\Module as EndModule;
use Modules\Company\API\GetById\GetById;
use Modules\Company\API\GetById\Module as GetByIdModule;
use Modules\Company\API\InformationUpdate\InformationUpdate;
use Modules\Company\API\InformationUpdate\Module as InformationUpdateModule;
use Modules\Company\API\Restore\Module as RestoreModule;
use Modules\Company\API\Restore\Restore;
use Modules\Company\API\Unblock\Module as UnblockModule;
use Modules\Company\API\Unblock\Unblock;

class CompanyApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(Create::class, CreateModule::class);
        $this->app->bind(Activate::class, ActivateModule::class);
        $this->app->bind(Block::class, BlockModule::class);
        $this->app->bind(Unblock::class, UnblockModule::class);
        $this->app->bind(Delete::class, DeleteModule::class);
        $this->app->bind(Restore::class, RestoreModule::class);
        $this->app->bind(AboutUpdate::class, AboutUpdateModule::class);
        $this->app->bind(ContactsUpdate::class, ContactsUpdateModule::class);
        $this->app->bind(InformationUpdate::class, InformationUpdateModule::class);
        $this->app->bind(GetById::class, GetByIdModule::class);
        $this->app->bind(End::class, EndModule::class);
        $this->app->bind(OrderCreate::class, OrderCreateModule::class);
        $this->app->bind(OrderGetById::class, OrderGetByIdModule::class);
        $this->app->bind(OrderList::class, OrderListModule::class);
        $this->app->bind(OrderUpdate::class, OrderUpdateModule::class);
    }
}
