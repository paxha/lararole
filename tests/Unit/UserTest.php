<?php

namespace Lararole\Tests\Unit;

use Lararole\Models\Role;
use Illuminate\Http\Request;
use Lararole\Tests\TestCase;
use Lararole\Tests\Models\User;

class UserTest extends TestCase
{
    public function testAssignSuperAdminRole()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        $user->assignSuperAdminRole();

        $this->assertCount(1, $user->roles);
    }

    public function testAssignRoles()
    {
        Role::create([
            'name' => 'Super Admin',
        ]);

        Role::create([
            'name' => 'Product Admin',
        ]);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        $user->assignRoles(\role()->all()->pluck('id')->toArray());

        $this->assertCount(2, $user->roles);
    }

    public function testRemoveRoles()
    {
        Role::create([
            'name' => 'Super Admin',
        ]);

        Role::create([
            'name' => 'Product Admin',
        ]);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        $user->removeRoles(\role()->all()->pluck('id')->toArray());

        $this->assertCount(0, $user->roles);
    }

    public function testRemoveAllRoles()
    {
        Role::create([
            'name' => 'Super Admin',
        ]);

        Role::create([
            'name' => 'Product Admin',
        ]);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        $user->removeAllRoles();

        $this->assertCount(0, $user->roles);
    }

    public function testModules()
    {
        $this->artisan('migrate:modules');

        $roleUserAdmin = Role::create([
            'name' => 'User Admin',
        ]);

        $modules[0]['module_id'] = 5;
        $modules[0]['permission'] = 'write';

        $request = new Request([
            'modules' => $modules,
        ]);

        $roleUserAdmin->assignModules($request);

        $roleProductEditor = Role::create([
            'name' => 'Product Editor',
        ]);

        $modules[0]['module_id'] = 1;
        $modules[0]['permission'] = 'read';

        $request = new Request([
            'modules' => $modules,
        ]);

        $roleProductEditor->assignModules($request);

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        $user->assignRoles([$roleUserAdmin->id, $roleProductEditor->id]);

        $this->assertCount(7, $user->modules);

        $this->assertCount(4, $user->modules()->where('permission', 'read')->get());
        $this->assertCount(3, $user->modules()->where('permission', 'write')->get());
    }
}
