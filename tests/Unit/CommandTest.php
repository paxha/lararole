<?php

namespace Lararole\Tests\Unit;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Tests\TestCase;
use Lararole\Tests\Models\User;
use Illuminate\Support\Facades\Config;

class CommandTest extends TestCase
{
    private $moduleViews = [
        'modules.product.inventory.product_listing',
        'modules.product.brand',
        'modules.user_management.user_1',
        'modules.user_management.role',
        'modules.order_processing.new_orders',
        'modules.order_processing.dispatched',
        'modules.others',
    ];

    private $excludeModuleViews = [
        'modules.product',
        'modules.product.inventory',
        'modules.user_management',
        'modules.order_processing',
    ];

    private $new_modules = [
        [
            'name' => 'Product',
            'icon' => 'icon-product',
            'modules' => [
                [
                    'name' => 'Inventory',
                    'modules' => [
                        ['name' => 'Product Listing'],
                    ],
                ],
                ['name' => 'Brand'],
                ['name' => 'Supplier'],
            ],
        ],
        [
            'name' => 'User Management',
            'icon' => 'icon-user',
            'modules' => [
                [
                    'name' => 'User',
                    'icon' => 'icon-user',
                ],
                [
                    'name' => 'Role',
                    'icon' => 'icon-role',
                ],
            ],
        ],
        [
            'name' => 'Order Processing',
            'icon' => 'icon-order',
            'modules' => [
                ['name' => 'New Orders'],
                ['name' => 'Dispatched'],
            ],
        ],
        [
            'name' => 'Others',
            'icon' => 'icon-others',
        ],
    ];

    public function testMigrateModulesCommand()
    {
        $this->artisan('migrate:modules');

        $this->assertCount(11, Module::all());
    }

    public function testMigrateModulesWithSyncCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        Config::set('lararole.modules', $this->new_modules);

        $this->artisan('migrate:modules --sync');

        $this->assertEquals(12, Module::whereSlug('supplier')->first()->id);

        $superAdminRole = Role::whereSlug('super-admin')->first();

        $this->assertCount(12, $superAdminRole->modules);
    }

    public function testMakeViewsCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:views');

        foreach ($this->moduleViews as $moduleView) {
            $this->assertTrue(view()->exists($moduleView.'.create'));
            $this->assertTrue(view()->exists($moduleView.'.edit'));
            $this->assertTrue(view()->exists($moduleView.'.index'));
            $this->assertTrue(view()->exists($moduleView.'.show'));
        }

        foreach ($this->excludeModuleViews as $excludeModuleView) {
            $this->assertFalse(view()->exists($excludeModuleView.'.create'));
            $this->assertFalse(view()->exists($excludeModuleView.'.edit'));
            $this->assertFalse(view()->exists($excludeModuleView.'.index'));
            $this->assertFalse(view()->exists($excludeModuleView.'.show'));
        }
    }

    public function testMakeSuperAdminRoleCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        $this->assertDatabaseHas('roles', [
            'name' => 'Super Admin',
            'slug' => 'super-admin',
        ]);

        $superAdminRole = Role::whereSlug('super-admin')->first();

        $this->assertCount(11, $superAdminRole->modules);
    }

    public function testAssignSuperAdminRoleCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('assign-super-admin-role --user='.$user->id);

        $this->assertCount(1, $user->roles);
    }

    public function testDBSeed()
    {
        $this->artisan('db:seed', ['--class' => '\Lararole\Database\Seeds\LararoleSeeder']);

        $this->assertCount(11, Module::all());

        $this->assertDatabaseHas('roles', [
            'name' => 'Super Admin',
            'slug' => 'super-admin',
        ]);

        $superAdminRole = Role::whereSlug('super-admin')->first();

        $this->assertCount(11, $superAdminRole->modules);

        $this->assertCount(4, Role::all());
    }
}
