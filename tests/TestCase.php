<?php

namespace Lararole\Tests;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Tests\Models\User;
use Lararole\LararoleServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $super_admin, $admin, $product_admin, $product_editor, $order_manager;

    protected $super_admin_role, $admin_read_modules_role, $admin_write_modules_role, $product_admin_role, $product_editor_role, $order_manager_role;

    protected $super_admin_modules, $admin_read_modules, $admin_write_modules, $product_admin_module, $product_editor_modules, $order_manager_modules;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');

        $this->withFactories(__DIR__.'/database/factories');
        $this->withFactories(__DIR__.'/../src/database/factories');

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
                        'modules' => [
                            ['name' => 'Product Listing'],
                        ],
                    ],
                    ['name' => 'Brand'],
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
                    ['name' => 'New Orders'],
                    ['name' => 'Dispatched'],
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

        $this->assertEquals(['product', 'user_management', 'order_processing'], Module::root()->get()->pluck('slug')->toArray());

        /*Super Admin Role*/
        $this->super_admin_role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'super_admin',
        ]);

        $this->super_admin_modules = Module::root()->get();

        $this->super_admin_role->modules()->attach($this->super_admin_modules, ['permission' => 'write']);

        $this->super_admin_modules = $this->super_admin_role->modules;

        $this->assertEquals(['product', 'user_management', 'order_processing', 'inventory', 'brand', 'product_listing', 'user', 'role', 'new_orders', 'dispatched'], $this->super_admin_modules->pluck('slug')->toArray());

        foreach ($this->super_admin_role->modules as $module) {
            $this->assertEquals('write', $module->permission->permission);
        }

        $this->super_admin = User::create([
            'name' => 'Super Admin',
        ]);

        $this->super_admin->roles()->attach($this->super_admin_role);

        $this->assertEquals(['super_admin'], $this->super_admin->roles()->pluck('slug')->toArray());
        /*End Super Admin Role*/

        /*Admin Role*/
        $this->admin_read_modules_role = Role::create([
            'name' => 'Admin Read Modules',
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'admin_read_modules',
        ]);

        $this->admin_write_modules_role = Role::create([
            'name' => 'Admin Write Modules',
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'admin_write_modules',
        ]);

        $this->admin_read_modules = Module::root()->where('slug', '!=', 'user_management')->get();

        $this->admin_read_modules_role->modules()->attach($this->admin_read_modules, ['permission' => 'read']);

        $this->admin_write_modules = Module::root()->where('slug', '=', 'user_management')->get();

        $this->admin_write_modules_role->modules()->attach($this->admin_write_modules, ['permission' => 'write']);

        $this->admin_read_modules = $this->admin_read_modules_role->modules;

        $this->assertEquals(['product', 'order_processing', 'inventory', 'brand', 'product_listing', 'new_orders', 'dispatched'], $this->admin_read_modules->pluck('slug')->toArray());

        foreach ($this->admin_read_modules_role->modules as $module) {
            $this->assertEquals('read', $module->permission->permission);
        }

        $this->admin_write_modules = $this->admin_write_modules_role->modules;

        $this->assertEquals(['user_management', 'user', 'role'], $this->admin_write_modules->pluck('slug')->toArray());

        foreach ($this->admin_write_modules_role->modules as $module) {
            $this->assertEquals('write', $module->permission->permission);
        }

        $this->admin = User::create([
            'name' => 'Admin',
        ]);

        $this->admin->roles()->attach($this->admin_read_modules_role);
        $this->admin->roles()->attach($this->admin_write_modules_role);

        $this->assertEquals(['admin_read_modules', 'admin_write_modules'], $this->admin->roles()->pluck('slug')->toArray());
        /*End Admin Role*/

        /*Product Admin Role*/
        $this->product_admin_role = Role::create([
            'name' => 'Product Admin',
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'product_admin',
        ]);

        $this->product_admin_module = Module::whereSlug('product')->first();

        $this->product_admin_role->modules()->attach($this->product_admin_module, ['permission' => 'write']);

        $this->product_admin_module = $this->product_admin_role->modules;

        $this->assertEquals(['product', 'inventory', 'brand', 'product_listing'], $this->product_admin_module->pluck('slug')->toArray());

        foreach ($this->product_admin_role->modules as $module) {
            $this->assertEquals('write', $module->permission->permission);
        }

        $this->product_admin = User::create([
            'name' => 'Product Admin',
        ]);

        $this->product_admin->roles()->attach($this->product_admin_role);

        $this->assertEquals(['product_admin'], $this->product_admin->roles()->pluck('slug')->toArray());
        /*End Product Admin Role*/

        /*Product Editor Role*/
        $this->product_editor_role = Role::create([
            'name' => 'Product Editor',
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'product_editor',
        ]);

        $this->product_editor_modules = Module::whereSlug('inventory')->first();

        $this->product_editor_role->modules()->attach($this->product_editor_modules, ['permission' => 'write']);

        $this->product_editor_modules = $this->product_editor_role->modules;

        $this->assertEquals(['inventory', 'product_listing'], $this->product_editor_modules->pluck('slug')->toArray());

        foreach ($this->product_editor_role->modules as $module) {
            $this->assertEquals('write', $module->permission->permission);
        }

        $this->product_editor = User::create([
            'name' => 'Product Editor',
        ]);

        $this->product_editor->roles()->attach($this->product_editor_role);

        $this->assertEquals(['product_editor'], $this->product_editor->roles()->pluck('slug')->toArray());
        /*End Product Editor Role*/

        /*Order Manager Role*/
        $this->order_manager_role = Role::create([
            'name' => 'Order Manager',
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'order_manager',
        ]);

        $this->order_manager_modules = Module::whereSlug('order_processing')->first();

        $this->order_manager_role->modules()->attach($this->order_manager_modules, ['permission' => 'write']);

        $this->order_manager_modules = $this->order_manager_role->modules;

        $this->assertEquals(['order_processing', 'new_orders', 'dispatched'], $this->order_manager_modules->pluck('slug')->toArray());

        foreach ($this->order_manager_role->modules as $module) {
            $this->assertEquals('write', $module->permission->permission);
        }

        $this->order_manager = User::create([
            'name' => 'Order Manager',
        ]);

        $this->order_manager->roles()->attach($this->order_manager_role);

        $this->assertEquals(['order_manager'], $this->order_manager->roles()->pluck('slug')->toArray());
        /*End Order Manager Role*/
    }
}
