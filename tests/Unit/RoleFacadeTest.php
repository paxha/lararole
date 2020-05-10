<?php

namespace Lararole\Tests\Unit;

use Lararole\Facades\Role;
use Lararole\Tests\TestCase;

class RoleFacadeTest extends TestCase
{
    public function testSuperAdminRole()
    {
        \Lararole\Models\Role::create([
            'name' => 'Super Admin',
        ]);

        $this->assertIsObject(Role::superAdminRole());
    }

    public function testHasSuperAdminRole()
    {
        $this->assertFalse(Role::hasSuperAdminRole());

        \Lararole\Models\Role::create([
            'name' => 'Super Admin',
        ]);

        $this->assertTrue(Role::hasSuperAdminRole());
    }

    public function testSyncSuperAdminRoleModules()
    {
        $role = \Lararole\Models\Role::create([
            'name' => 'Super Admin',
        ]);

        $this->assertCount(0, $role->modules);

        $this->artisan('migrate:modules');

        Role::syncSuperAdminRoleModules();

        $role = \Lararole\Models\Role::whereSlug('super-admin')->first();

        $this->assertCount(0, $role->modules()->wherePivot('permission', 'read')->get());
        $this->assertCount(11, $role->modules()->wherePivot('permission', 'write')->get());
    }
}
