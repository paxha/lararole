<?php


namespace Lararole\Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Lararole\Tests\Models\User;
use Lararole\Tests\TestCase;

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