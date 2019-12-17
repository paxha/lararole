<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Tests\TestCase;

class RoleTest extends TestCase
{
    public function testAssignModules()
    {
        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'user_management'])->get()->pluck('id')->toArray();

        $role->assignModules($modules, ['read', 'write']);

        $this->assertCount(7, $role->modules);

        $this->assertCount(4, $role->modules()->wherePivot('permission', 'read')->get());
        $this->assertCount(3, $role->modules()->wherePivot('permission', 'write')->get());
    }

    public function testDetachModules()
    {
        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get()->pluck('id')->toArray();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $module = Module::whereSlug('settings')->first();

        $role->modules()->attach($module, ['permission' => 'write']);

        $role->removeModules($modules);

        $this->assertCount(1, $role->modules);
    }

    public function testDetachAllModules()
    {
        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('migrate:modules');

        $modules = Module::whereIn('slug', ['product', 'order_processing'])->get()->pluck('id')->toArray();

        $role->modules()->attach($modules, ['permission' => 'write']);

        $module = Module::whereSlug('settings')->first();

        $role->modules()->attach($module, ['permission' => 'write']);

        $role->removeAllModules($modules);

        $this->assertCount(0, $role->modules);
    }
}
