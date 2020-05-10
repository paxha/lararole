<?php

namespace Lararole;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Lararole\Models\Module;
use Lararole\Models\Role;
use Lararole\Policies\ModulePolicy;
use Lararole\Policies\RolePolicy;

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
