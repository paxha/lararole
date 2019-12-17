<?php

namespace Lararole\Tests\Feature;

use Illuminate\Http\Request;
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

        $modules[0]['module_id'] = 1;
        $modules[0]['permission'] = 'read';
        $modules[1]['module_id'] = 5;
        $modules[1]['permission'] = 'write';

        $request = new Request([
            'modules' => $modules,
        ]);

        $role->assignModules($request);

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
