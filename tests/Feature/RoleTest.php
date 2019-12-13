<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Models\Role;
use Lararole\Tests\TestCase;

class RoleTest extends TestCase
{
    public function testCreateRole()
    {
        Role::create([
            'name' => 'Super Admin'
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Super Admin',
            'slug' => 'super_admin',
        ]);
    }

    public function testAttachModule()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $module = Module::whereSlug('product')->first();

        $role->modules()->attach($module, ['permission' => 'write']);

        $this->assertEquals(['product', 'inventory', 'brand', 'product_listing'], $role->modules()->pluck('slug')->toArray());
    }

    public function testAttachModules()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $this->assertEquals(['order_processing', 'product', 'new_orders', 'dispatched', 'inventory', 'brand', 'product_listing'], $role->modules()->pluck('slug')->toArray());
    }

    public function testAttachModulesWithPivot()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules[8]['module_id'] = 8; /*Order Processing*/
        $modules[8]['permission'] = 'write';
        $modules[1]['module_id'] = 1; /*Product*/
        $modules[1]['permission'] = 'read';

        $role->modules()->attach($modules);

        $this->assertEquals(['order_processing', 'product', 'new_orders', 'dispatched', 'inventory', 'brand', 'product_listing'], $role->modules()->pluck('slug')->toArray());

        foreach ($role->modules()->whereIn('slug', ['order_processing', 'new_orders', 'dispatched'])->get() as $module) {
            $this->assertEquals('write', $module->permission->permission);
        }
        foreach ($role->modules()->whereIn('slug', ['product', 'inventory', 'brand', 'product_listing'])->get() as $module) {
            $this->assertEquals('read', $module->permission->permission);
        }
    }

    public function testAttachModulesWithoutPivot()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get();

        $role->modules()->attach($modules);

        $this->assertEquals(['order_processing', 'product', 'new_orders', 'dispatched', 'inventory', 'brand', 'product_listing'], $role->modules()->pluck('slug')->toArray());

        foreach ($role->modules as $module) {
            $this->assertEquals('read', $module->permission->permission);
        }
    }

    public function testAttachModuleWithOldModules()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $module = Module::whereSlug('settings')->first();

        $role->modules()->attach($module, ['permission' => 'write']);

        $this->assertEquals(['order_processing', 'product', 'new_orders', 'dispatched', 'inventory', 'brand', 'product_listing', 'settings'], $role->modules()->pluck('slug')->toArray());
    }

    public function testAttachModulesWithOldModules()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $modules = Module::whereIn('slug', ['settings', 'user_management'])->get();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $this->assertEquals(['order_processing', 'product', 'new_orders', 'dispatched', 'inventory', 'brand', 'product_listing', 'settings', 'user_management', 'user', 'role'], $role->modules()->pluck('slug')->toArray());
    }

    public function testSyncModule()
    {
        $this->assertTrue(true);
    }

    public function testSyncModules()
    {
        $this->assertTrue(true);
    }

    public function testSyncModulesWithPivot()
    {
        $this->assertTrue(true);
    }

    public function testSyncModulesWithoutPivot()
    {
        $this->assertTrue(true);
    }

    public function testSyncModuleWithOldModules()
    {
        $this->assertTrue(true);
    }

    public function testSyncModulesWithOldModules()
    {
        $this->assertTrue(true);
    }

    public function testDetachModule()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $module = Module::whereSlug('settings')->first();

        $role->modules()->attach($module, ['permission' => 'write']);

        $role->modules()->detach($module);

        $this->assertEquals(['order_processing', 'product', 'new_orders', 'dispatched', 'inventory', 'brand', 'product_listing'], $role->modules()->pluck('slug')->toArray());
    }

    public function testDetachModules()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $module = Module::whereSlug('settings')->first();

        $role->modules()->attach($module, ['permission' => 'write']);

        $role->modules()->detach($modules);

        $this->assertEquals(['settings'], $role->modules()->pluck('slug')->toArray());
    }

    public function testDetachAllModule()
    {
        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $module = Module::whereSlug('settings')->first();

        $role->modules()->attach($module, ['permission' => 'write']);

        $role->modules()->detach();

        $this->assertEquals([], $role->modules()->pluck('slug')->toArray());
    }
}
