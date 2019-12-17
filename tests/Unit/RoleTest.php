<?php

namespace Lararole\Tests\Unit;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Illuminate\Http\Request;
use Lararole\Tests\TestCase;

class RoleTest extends TestCase
{
    public function testAssignModules()
    {
        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('migrate:modules');

        $modules[0]['module_id'] = Module::whereSlug('product')->first()->id;
        $modules[0]['permission'] = 'read';
        $modules[1]['module_id'] = Module::whereSlug('user_management')->first()->id;
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

        $modules[0]['module_id'] = Module::whereSlug('product')->first()->id;
        $modules[0]['permission'] = 'read';
        $modules[1]['module_id'] = Module::whereSlug('user_management')->first()->id;
        $modules[1]['permission'] = 'write';

        $request = new Request([
            'modules' => $modules,
        ]);

        $role->assignModules($request);

        $role->removeModules([Module::whereSlug('product')->first()->id]);

        $this->assertCount(3, $role->modules);
    }

    public function testDetachAllModules()
    {
        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('migrate:modules');

        $modules[0]['module_id'] = Module::whereSlug('product')->first()->id;
        $modules[0]['permission'] = 'read';
        $modules[1]['module_id'] = Module::whereSlug('user_management')->first()->id;
        $modules[1]['permission'] = 'write';

        $request = new Request([
            'modules' => $modules,
        ]);

        $role->assignModules($request);

        $role->removeAllModules($modules);

        $this->assertCount(0, $role->modules);
    }
}
