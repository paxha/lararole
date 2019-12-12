<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Models\Role;
use Lararole\Tests\TestCase;

class CommandTest extends TestCase
{
    public function testMakeSuperAdminCommand()
    {
        Role::query()->truncate();

        $this->artisan('make:super-admin-role');

        $this->assertDatabaseHas('roles', [
            'slug' => 'super_admin',
        ]);
    }

    public function testMigrateModulesCommand()
    {
        foreach (Module::all() as $module) {
            $module->delete();
        }

        $this->artisan('migrate:modules');

        $this->assertNotEmpty(Module::all(), 'Modules data should be exist in db');
    }
}
