<?php

namespace Eiixy\Rbac;

use Illuminate\Support\ServiceProvider;


class RbacServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $config_path = realpath(__DIR__.'/../../config/rbac.php');
        $this->publishes([
            $config_path => database_path('config/rbac.php'),
        ], 'rbac-config');
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'rbac-migrations');
    }
}
