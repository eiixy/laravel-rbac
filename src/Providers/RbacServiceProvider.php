<?php

namespace Eiixy\Rbac;

use Illuminate\Support\ServiceProvider;


class RbacServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 发布配置文件
        $config_path = realpath(__DIR__.'/../../config/rbac.php');
        $this->publishes([
            $config_path => database_path('config/rbac.php'),
        ], 'rbac-config');

        // 发布数据库迁移文件
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'rbac-migrations');

        //
    }
}
