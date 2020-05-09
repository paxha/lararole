<?php

namespace Lararole\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Lararole\Models\Role;
use Lararole\Models\Module;
use Illuminate\Http\Request;
use Lararole\Tests\TestCase;
use Lararole\Tests\Models\User;

class ModuleTest extends TestCase
{
    public function testUsers()
    {
        $this->artisan('migrate:modules');

        $roleUserAdmin = Role::create([
            'name' => 'User Admin',
        ]);

        $modules[0]['module_id'] = Module::whereSlug('user_management')->first()->id;
        $modules[0]['permission'] = 'write';

        $request = new Request([
            'modules' => $modules,
        ]);

        $roleUserAdmin->assignModules($request);

        $roleProductEditor = Role::create([
            'name' => 'Product Editor',
        ]);

        $modules[0]['module_id'] = Module::whereSlug('product')->first()->id;
        $modules[0]['permission'] = 'read';

        $request = new Request([
            'modules' => $modules,
        ]);

        $roleProductEditor->assignModules($request);

        $userSuperAdmin = User::create([
            'name' => 'Super Admin',
        ]);

        $userSuperAdmin->assignRoles([$roleUserAdmin->id, $roleProductEditor->id]);

        $userProductEditor = User::create([
            'name' => 'Product Editor',
        ]);

        $userProductEditor->assignRoles([$roleProductEditor->id]);

        $this->assertCount(2, Module::whereSlug('product')->first()->users);
        $this->assertCount(1, Module::whereSlug('user_management')->first()->users);
    }

    public function testLoggableTrue()
    {
        Config::set('lararole.loggable', true);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $role = Module::create([
            'name' => 'Super Admin',
        ]);

        $this->assertEquals(auth()->user()->id, $role->created_by);
    }

    public function testLoggableFalse()
    {
        Config::set('lararole.loggable', false);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $role = Module::create([
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

        $role = Module::create([
            'name' => 'Product',
        ]);

        $this->assertEquals(auth()->user()->name, $role->creator->name);
    }

    public function testUpdater()
    {
        $user = User::create([
            'name' => 'Product',
        ]);

        auth()->login($user);

        $module = Module::create([
            'name' => 'Product',
        ]);

        $module->update([
            'name' => 'Product Updated',
        ]);

        $this->assertEquals(auth()->user()->name, $module->updater->name);
    }

    public function testDeleter()
    {
        $user = User::create([
            'name' => 'Super Admin',
        ]);

        auth()->login($user);

        $module = Module::create([
            'name' => 'Product',
        ]);

        $module->delete();

        $this->assertEquals(auth()->user()->name, $module->deleter->name);
    }
}
