<?php

namespace Lararole;

use Lararole\Services\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sven\ArtisanView\Commands\MakeView;
use Lararole\Console\Commands\InstallCommand;
use Lararole\Console\Commands\MakeViewsCommand;
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
        $this->app->bind('role', function () {
            return new Role();
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

        /*Migrations Publishable*/
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'lararole-migrations');
        $this->registerMigrations();

        /*Config Publishable*/
        $this->publishes([
            __DIR__.'/../config/lararole.php' => config_path('lararole.php'),
        ], 'lararole-config');

        /*Views Publishable*/
        $this->publishes([
            __DIR__.'/../resources/views/access_denied.blade.php' => base_path('resources/views'),
        ], 'lararole-views');
        $this->registerViews();

        /*Routes Publishable*/
        $this->publishes([
            __DIR__.'/Http/Controllers/ModuleController.php' => app_path('Http/Controllers/ModuleController.php'),
            __DIR__.'/../routes/web.php' => base_path('routes/module.web.php'),
        ], 'lararole-routes');
        $this->registerRoutes();

        /*Assets Publishable*/
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/lararole'),
        ], 'lararole-assets');

        $this->commands([
            MakeSuperAdminRoleCommand::class,
            InstallCommand::class,
            MakeView::class,
            MakeViewsCommand::class,
            MigrateModulesCommand::class,
            AssignSuperAdminRoleCommand::class,
        ]);

        $this->configureMiddleware();
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lararole');
    }

    /**
     * Register Lararole's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Lararole::shouldRunMigrations()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
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
            $this->loadRoutesFrom(__DIR__.'/../routes/module.php');

            Route::group($this->apiRoutesConfiguration(), function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
            });

            Route::group($this->webRoutesConfiguration(), function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
        }
    }

    private function webRoutesConfiguration()
    {
        return [
            'namespace' => 'Lararole\Http\Controllers',
            'prefix' => 'lararole',
            'middleware' => 'web',
        ];
    }

    private function apiRoutesConfiguration()
    {
        return [
            'namespace' => 'Lararole\Http\Controllers\Api',
            'prefix' => 'lararole/api',
            'middleware' => 'api',
        ];
    }

    /**
     * Register Lararole's views files.
     *
     * @return void
     */
    protected function registerViews()
    {
        if (Lararole::shouldRunViews()) {
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'lararole');
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
