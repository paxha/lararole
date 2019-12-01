<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Tests\TestCase;

class CommandTest extends TestCase
{
    public function testMakeSuperAdminCommand()
    {
        $this->artisan('make:super-admin-role');

        $this->assertDatabaseHas('roles', [
            'slug' => 'super_admin',
        ]);
    }

    public function testMigrateModulesCommand()
    {
        Module::query()->truncate();

        $this->assertEmpty(Module::all(), 'Modules data should be empty');

        $this->artisan('migrate:modules');

        $this->assertNotEmpty(Module::all(), 'Modules data should be exist in db');
    }
}
