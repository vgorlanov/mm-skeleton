<?php

declare(strict_types=1);

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\API\Info\GetById\{GetById as InfoGetById, Module as InfoGetByIdModule};
use Modules\User\API\System\Block\Block;
use Modules\User\API\System\Block\Module as BlockModule;
use Modules\User\API\System\Create\Create;
use Modules\User\API\System\Create\Module as CreateModule;
use Modules\User\API\System\CredentialUpdate\CredentialUpdate;
use Modules\User\API\System\CredentialUpdate\Module as CredentialUpdateModule;
use Modules\User\API\System\DataUpdate\DataUpdate;
use Modules\User\API\System\DataUpdate\Module as DataUpdateModule;
use Modules\User\API\System\Delete\Delete;
use Modules\User\API\System\Delete\Module as DeleteModule;
use Modules\User\API\System\Restore\Module as RestoreModule;
use Modules\User\API\System\Restore\Restore;
use Modules\User\API\System\UnBlock\Module as UnBlockModule;
use Modules\User\API\System\UnBlock\UnBlock;

final class UserApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // System
        $this->app->bind(Create::class, CreateModule::class);
        $this->app->bind(Delete::class, DeleteModule::class);
        $this->app->bind(Restore::class, RestoreModule::class);
        $this->app->bind(Block::class, BlockModule::class);
        $this->app->bind(UnBlock::class, UnBlockModule::class);
        $this->app->bind(CredentialUpdate::class, CredentialUpdateModule::class);
        $this->app->bind(DataUpdate::class, DataUpdateModule::class);

        // Info
        $this->app->bind(InfoGetById::class, InfoGetByIdModule::class);
    }
}
