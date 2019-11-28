<?php


namespace Lararole\Tests\Feature;


use Lararole\Models\Module;
use Lararole\Models\Role;
use Lararole\Tests\TestCase;

class CommandTest extends TestCase
{
    public function testMakeSuperAdminCommand()
    {
        $this->artisan('make:super-admin-role');

        $this->assertDatabaseHas('roles', [
            'slug' => 'super_admin'
        ]);
    }
}