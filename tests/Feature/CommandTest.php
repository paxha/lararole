<?php

namespace Lararole\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Tests\TestCase;

class CommandTest extends TestCase
{
    private $moduleViews = [
        'modules.product.inventory.product_listing',
        'modules.product.brand',
        'modules.user_management.user',
        'modules.user_management.role',
        'modules.order_processing.new_orders',
        'modules.order_processing.dispatched',
        'modules.settings',
    ];

    private $excludeModuleViews = [
        'modules.product',
        'modules.product.inventory',
        'modules.user_management',
        'modules.order_processing',
    ];

    public function testMigrateModulesCommand()
    {
        $this->artisan('migrate:modules');

        $this->assertEquals(['Product', 'Inventory', 'Product Listing', 'Brand', 'User Management', 'User', 'Role', 'Order Processing', 'New Orders', 'Dispatched', 'Settings'], Module::all()->pluck('name')->toArray());
    }

    public function testMakeViewsCommand()
    {
        $this->artisan('make:views');

        foreach ($this->moduleViews as $moduleView) {
            $this->assertTrue(view()->exists($moduleView . '.create'));
            $this->assertTrue(view()->exists($moduleView . '.edit'));
            $this->assertTrue(view()->exists($moduleView . '.index'));
            $this->assertTrue(view()->exists($moduleView . '.show'));
        }

        foreach ($this->excludeModuleViews as $excludeModuleView) {
            $this->assertFalse(view()->exists($excludeModuleView . '.create'));
            $this->assertFalse(view()->exists($excludeModuleView . '.edit'));
            $this->assertFalse(view()->exists($excludeModuleView . '.index'));
            $this->assertFalse(view()->exists($excludeModuleView . '.show'));
        }
    }

    public function testMakeSuperAdminRoleCommand()
    {
        $this->artisan('make:super-admin-role');

        $this->assertDatabaseHas('roles', [
            'slug' => 'super_admin',
        ]);
    }

    public function testDBSeed()
    {
        $this->artisan('db:seed', ['--class' => '\Lararole\Database\Seeds\LararoleSeeder']);

        $this->assertEquals(['Product', 'Inventory', 'Product Listing', 'Brand', 'User Management', 'User', 'Role', 'Order Processing', 'New Orders', 'Dispatched', 'Settings'], Module::all()->pluck('name')->toArray());

        $this->assertDatabaseHas('roles', [
            'name' => 'Super Admin',
            'slug' => 'super_admin'
        ]);

        $this->assertCount(4, DB::table('roles')->get());
    }
}
