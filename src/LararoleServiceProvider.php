<?php

namespace Lararole;

use DBTruncate\Console\DBTruncate;
use Illuminate\Support\ServiceProvider;
use Lararole\Console\Commands\MakeViewsCommand;
use Lararole\Console\Commands\MigrateModulesCommand;
use Lararole\Http\Middleware\ModuleHasReadPermission;
use Lararole\Http\Middleware\ModuleHasWritePermission;
use Lararole\Console\Commands\MakeSuperAdminRoleCommand;

class LararoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/lararole.php' => config_path('lararole.php'),
        ]);

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->app->make('Illuminate\Database\Eloquent\Factory')->load(__DIR__.'/database/factories');

        $this->app['router']->aliasMiddleware('permission.read', ModuleHasReadPermission::class);
        $this->app['router']->aliasMiddleware('permission.write', ModuleHasWritePermission::class);

        $this->loadRoutesFrom(__DIR__.'/routes/web.module.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeSuperAdminRoleCommand::class,
                MakeViewsCommand::class,
                MigrateModulesCommand::class,
                DBTruncate::class,
            ]);
        }
    }
}
