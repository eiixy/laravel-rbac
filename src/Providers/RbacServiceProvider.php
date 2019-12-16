<?php

namespace Eiixy\Rbac\Providers;

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

        $this->publishes([
            __DIR__.'/../../database/seeds' => database_path('seeds'),
        ], 'rbac-seeds');

        // 加载数据库迁移文件
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');


        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        //
    }



    protected function loadMigrationsFrom($paths)
    {
        $this->app->afterResolving('migrator', function ($migrator) use ($paths) {
            foreach ((array) $paths as $path) {
                $migrator->path($path);
            }
        });
    }
}
