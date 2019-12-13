<?php

namespace Lararole\Tests\Feature;

use Lararole\Tests\TestCase;

class RoleTest extends TestCase
{
    public function testDetach()
    {
        $this->super_admin->roles()->detach();

        $this->assertEquals([], $this->super_admin->roles()->pluck('slug')->toArray());

        $this->admin->roles()->detach($this->admin_write_modules_role);

        $this->assertEquals(['admin_read_modules'], $this->admin->roles()->pluck('slug')->toArray());
    }

    public function testSync()
    {
//        Role::query()->truncate();
//
//        $admin_read_modules = Module::root()->where('slug', '=', 'user_management')->get();
//
//        $admin_role = Role::create([
//            'name' => 'Admin',
//        ]);
//
//        $this->assertDatabaseHas('roles', [
//            'slug' => 'admin',
//        ]);
//
//        $admin_role->modules()->sync($admin_read_modules, ['permission' => 'read']);
//
//        $admin_read_modules = $admin_role->modules;
//
//        $this->assertEquals(['user_management'], $admin_read_modules->pluck('slug')->toArray());
//
//        $admin_write_modules = Module::root()->where('slug', '!=', 'user_management')->get();
//
//        $admin_role->modules()->sync($admin_write_modules, ['permission' => 'write']);
//
//        $admin_write_modules = $admin_role->modules;
//
//        $this->assertEquals(['product', 'order_processing'], $admin_write_modules->pluck('slug')->toArray());
    }
}
