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
        $this->app->make('Illuminate\Database\Eloquent\Factory')->load(__DIR__.'/../database/factories');

        if ($this->app->runningInConsole()) {
            /*Migrations Publishable*/
            $this->registerMigrations();
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'lararole-migrations');

            /*Config Publishable*/
            $this->publishes([
                __DIR__.'/../config/lararole.php' => config_path('lararole.php'),
            ], 'lararole-config');

            /*Views Publishable*/
            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views'),
            ], 'lararole-views');

            $this->registerRoutes();
            $this->publishes([
                __DIR__.'/../routes/web.php' => base_path('routes'),
                __DIR__.'/Http/Controllers/ModuleController.php' => app_path('Http/Controllers/ModuleController.php'),
            ], 'lararole-routes');

            $this->commands([
                MakeSuperAdminRoleCommand::class,
                MakeView::class,
                MakeViewsCommand::class,
                MigrateModulesCommand::class,
                AssignSuperAdminRoleCommand::class,
            ]);
        }

        $this->configureMiddleware();
    }

    /**
     * Register Lararole's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Lararole::shouldRunMigrations()) {
            return $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Register Lararole's routes files.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (Lararole::shouldRunRoutes()) {
            return $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
    }

    /**
     * Register Lararole's views files.
     *
     * @return void
     */
    protected function registerViews()
    {
        if (Lararole::shouldRunViews()) {
            return $this->loadViewsFrom(__DIR__.'/../resources/views');
        }
    }

    /**
     * Configure the Lararole middleware and priority.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        $this->app['router']->aliasMiddleware('permission.read', ModuleHasReadPermission::class);
        $this->app['router']->aliasMiddleware('permission.write', ModuleHasWritePermission::class);
    }
}
