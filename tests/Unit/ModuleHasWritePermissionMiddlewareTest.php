<?php

namespace Lararole\Tests\Unit;

use Illuminate\Http\Request;
use Lararole\Models\Module;
use Lararole\Models\Role;
use Lararole\Tests\Models\User;
use Lararole\Tests\TestCase;
use Lararole\Tests\Helper\Helper;
use Lararole\Http\Middleware\ModuleHasWritePermission;

class ModuleHasWritePermissionMiddlewareTest extends TestCase
{
    protected $moduleHasWritePermission;

    public function setUp(): void
    {
        parent::setUp();

        $this->moduleHasWritePermission = new ModuleHasWritePermission();
    }

    public function testModuleHasWritePermissionUnauthenticated()
    {
        $this->artisan('migrate:modules');

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 401);
        }
    }

    public function testModuleHasWritePermissionWithNoRole()
    {
        $this->artisan('migrate:modules');

        $user = User::create([
            'name' => 'Super Admin'
        ]);

        auth()->login($user);

        $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, 'product'), 302);
    }

    public function testModuleHasWritePermission()
    {
        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('migrate:modules');

        $modules[0]['module_id'] = Module::whereSlug('product')->first()->id;
        $modules[0]['permission'] = 'write';

        $request = new Request([
            'modules' => $modules,
        ]);

        $role->assignModules($request);

        $user = User::create([
            'name' => 'Super Admin'
        ]);

        $user->assignRoles([$role->id]);

        auth()->login($user);

        $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, 'product'), 200);
    }

    public function testModuleHasReadPermission()
    {
        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('migrate:modules');

        $modules[0]['module_id'] = Module::whereSlug('product')->first()->id;
        $modules[0]['permission'] = 'read';

        $request = new Request([
            'modules' => $modules,
        ]);

        $role->assignModules($request);

        $user = User::create([
            'name' => 'Super Admin'
        ]);

        $user->assignRoles([$role->id]);

        auth()->login($user);

        $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, 'product'), 302);
    }
}
