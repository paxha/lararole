<?php

namespace Lararole\Tests;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Tests\Models\User;
use Illuminate\Support\Facades\DB;
use Lararole\LararoleServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');

        $this->withFactories(__DIR__.'/database/factories');
        $this->withFactories(__DIR__.'/../src/database/factories');

        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $this->assertContainsEquals('users', $tables, 'users table must be exists');
        $this->assertContainsEquals('modules', $tables, 'modules table must be exists');
        $this->assertContainsEquals('roles', $tables, 'roles table must be exists');
        $this->assertContainsEquals('module_role', $tables, 'module_role table must be exists');
        $this->assertContainsEquals('role_user', $tables, 'role_user table must be exists');

        $this->seeds();
    }

    protected function getPackageProviders($app)
    {
        return [
            LararoleServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('lararole.providers.users.model', User::class);

        $modules = [
            [
                'name' => 'Product',
                'icon' => 'feather icon-layers',
                'modules' => [
                    [
                        'name' => 'Inventory',
                    ],
                    ['name' => 'Brand'],
                    ['name' => 'Category'],
                    ['name' => 'Unit'],
                    ['name' => 'Attribute'],
                ],
            ],
            [
                'name' => 'User Management',
                'icon' => 'feather icon-user',
                'modules' => [
                    ['name' => 'User'],
                    ['name' => 'Role'],
                ],
            ],
            [
                'name' => 'Order Processing',
                'icon' => 'feather icon-settings',
                'modules' => [
                    [
                        'name' => 'New',
                        'modules' => [
                            ['name' => 'New Order'],
                        ],
                    ],
                    ['name' => 'Dispatched'],
                    ['name' => 'Delivered'],
                    ['name' => 'Cancelled'],
                ],
            ],
        ];

        $app['config']->set('lararole.modules', $modules);
    }

    protected function seeds()
    {
        foreach (config('lararole.modules') as $module) {
            $m = Module::create([
                'name' => $module['name'],
                'icon' => @$module['icon'],
            ]);

            if (@$module['modules']) {
                $m->create_modules(@$module['modules']);
            }
        }

        factory(Role::class, 5)->create();

        Role::all()->each(function ($role) {
            $role->modules()->attach(Module::isRoot()->get()->random(rand(1, 3))->pluck('id')->toArray());
        });

        factory(User::class, 10)->create()->each(function ($user) {
            $user->roles()->attach(Role::all()->random(rand(1, 3))->pluck('id')->toArray());
        });

        $this->assertNotEmpty(Module::all(), 'Modules data not be empty');
        $this->assertNotEmpty(Role::all(), 'Modules data not be empty');
        $this->assertNotEmpty(Role::all()->random()->modules, 'Role modules data not be empty');
        $this->assertNotEmpty(User::all(), 'Users data not be empty');
        $this->assertNotEmpty(User::all()->random()->roles, 'User role data not be empty');
        $this->assertNotEmpty(User::all()->random()->modules, 'User modules data not be empty');
    }
}
