<?php

namespace Lararole\Tests\Feature;

use Illuminate\Http\Request;
use Lararole\Tests\TestCase;

class RoleFacadeTest extends TestCase
{
    public function testCreateRoleFacade()
    {
        $this->artisan('migrate:modules');

        $modules[0]['module_id'] = 1;
        $modules[0]['permission'] = 'write';
        $modules[1]['module_id'] = 5;
        $modules[1]['permission'] = 'write';

        $request = new Request([
            'name' => 'Super Admin',
            'modules' => $modules
        ]);

        $role = role()->create($request);

        $this->assertEquals('Super Admin', $role->name);

        $this->assertCount(7, $role->modules);
    }
}