<?php

namespace Lararole\Tests\Feature;

use Lararole\Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function testMigrations()
    {
        $this->artisan('migrate');

        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $this->assertContainsEquals('users', $tables, 'users table must be exists');
        $this->assertContainsEquals('modules', $tables, 'modules table must be exists');
        $this->assertContainsEquals('roles', $tables, 'roles table must be exists');
        $this->assertContainsEquals('module_role', $tables, 'module_role table must be exists');
        $this->assertContainsEquals('role_user', $tables, 'role_user table must be exists');
    }
}
