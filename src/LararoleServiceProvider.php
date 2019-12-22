<?php

namespace Lararole;

use Illuminate\Support\ServiceProvider;
use Sven\ArtisanView\Commands\MakeView;
use Lararole\Containers\RoleServiceContainer;
use Lararole\Console\Commands\MakeViewsCommand;
use Lararole\Containers\ModuleServiceContainer;
use Lararole\Console\Commands\MigrateModulesCommand;
use Lararole\Http\Middleware\ModuleHasReadPermission;
use Lararole\Http\Middleware\ModuleHasWritePermission;
use Lararole\Console\Commands\MakeSuperAdminRoleCommand;
use Lararole\Console\Commands\AssignSuperAdminRoleCommand;

class LararoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('module', function () {
            return new ModuleServiceContainer();
        });

        $this->app->bind('role', function () {
            return new RoleServiceContainer();
        });
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
            __DIR__.'/../config/lararole.php' => config_path('lararole.php'),
        ], 'lararole-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'lararole-migrations');

        $this->publishes([
            __DIR__.'/../database/factories' => database_path('factories'),
        ], 'lararole-factories');

        $this->publishes([
            __DIR__.'/../routes' => base_path('routes'),
        ], 'lararole-routes');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views'),
        ], 'lararole-views');

        $this->publishes([
            __DIR__.'./Http/Controllers' => app_path('Http/Controllers/ModuleController.php'),
        ], 'lararole-controller');

        $this->app['router']->aliasMiddleware('permission.read', ModuleHasReadPermission::class);
        $this->app['router']->aliasMiddleware('permission.write', ModuleHasWritePermission::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeSuperAdminRoleCommand::class,
                MakeView::class,
                MakeViewsCommand::class,
                MigrateModulesCommand::class,
                AssignSuperAdminRoleCommand::class,
            ]);
        }
    }
}
