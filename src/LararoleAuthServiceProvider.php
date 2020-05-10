<?php

namespace Lararole;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Policies\RolePolicy;
use Lararole\Policies\ModulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class LararoleAuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Module::class => ModulePolicy::class,
        Role::class => RolePolicy::class,
    ];

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
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
