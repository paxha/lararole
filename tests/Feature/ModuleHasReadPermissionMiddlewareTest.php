<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Tests\TestCase;
use Lararole\Tests\Helper\Admin;
use Lararole\Tests\Helper\Helper;
use Lararole\Tests\Helper\SuperAdmin;
use Lararole\Http\Middleware\ModuleHasReadPermission;

class ModuleHasReadPermissionMiddlewareTest extends TestCase
{
    protected $moduleHasReadPermission;

    public function setUp(): void
    {
        parent::setUp();

        $this->moduleHasReadPermission = new ModuleHasReadPermission();
    }

    public function testModuleHasReadPermissionUnauthenticated()
    {
        $this->artisan('migrate:modules');

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 401);
        }
    }

    public function testModuleHasReadPermissionWithNoRole()
    {
        $this->artisan('migrate:modules');

        $superAdmin = new SuperAdmin();

        auth()->login($superAdmin->user);

        $superAdmin->user->roles()->detach();

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }
    }

    public function testModuleHasReadPermission()
    {
        $superAdmin = new SuperAdmin();

        auth()->login($superAdmin->user);

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
    }

    public function testModuleHasReadWritePermission()
    {
        $admin = new Admin();

        auth()->login($admin->user);

        foreach ($admin->readRole->modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
        foreach ($admin->writeRole->modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
    }
}
