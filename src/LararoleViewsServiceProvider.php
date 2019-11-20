<?php

namespace Lararole;

use Illuminate\Support\ServiceProvider;

class LararoleViewsServiceProvider extends ServiceProvider
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
            __DIR__ . '/resources/views' => base_path('resources/views'),
        ]);
    }
}
