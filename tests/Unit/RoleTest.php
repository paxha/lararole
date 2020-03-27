<?php

namespace Lararole\Tests\Unit;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Illuminate\Http\Request;
use Lararole\Tests\TestCase;
use Lararole\Tests\Models\User;
use Illuminate\Support\Facades\Config;

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

    public function testActivable()
    {
        Role::create([
            'name' => 'Super Admin',
        ]);

        $role = Role::whereSlug('super-admin')->first();

        $this->assertEquals(true, $role->active);

        $role->toggleActive();

        $this->assertEquals(false, $role->active);

        $role->toggleActive();

        $this->assertEquals(true, $role->active);
    }

    public function testMarkAsActive()
    {
        Role::create([
            'name' => 'Super Admin',
        ]);

        $role = Role::whereSlug('super-admin')->first();

        $role->markAsActive();

        $this->assertEquals(true, $role->active);
    }

    public function testMarkAsInactive()
    {
        Role::create([
            'name' => 'Super Admin',
        ]);

        $role = Role::whereSlug('super-admin')->first();

        $role->markAsInactive();

        $this->assertEquals(false, $role->active);
    }

    public function testAttachAllChildrenTrue()
    {
        Config::set('lararole.attachAllChildren', true);

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
    }

    public function testAttachAllChildrenFalse()
    {
        Config::set('lararole.attachAllChildren', false);

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

        $this->assertCount(2, $role->modules);
    }

    public function testLoggableTrue()
    {
        Config::set('lararole.role.loggable', true);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->assertEquals(auth()->user()->id, $role->created_by);
    }

    public function testLoggableFalse()
    {
        Config::set('lararole.role.loggable', false);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->assertNotEquals(auth()->user()->id, $role->created_by);
    }

    public function testCreator()
    {
        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->assertEquals(auth()->user()->name, $role->creator->name);
    }

    public function testUpdater()
    {
        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $role->update([
            'name' => 'Super Admin Updated',
        ]);

        $this->assertEquals(auth()->user()->name, $role->updater->name);
    }

    public function testDeleter()
    {
        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $role->delete();

        $this->assertEquals(auth()->user()->name, $role->deleter->name);
    }
}
