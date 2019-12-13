<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Tests\TestCase;
use Lararole\Tests\Helper\Admin;
use Lararole\Tests\Helper\Helper;
use Lararole\Tests\Helper\SuperAdmin;
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
        $superAdmin = new SuperAdmin();

        auth()->login($superAdmin->user);

        $superAdmin->user->roles()->detach();

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }
    }

    public function testModuleHasWritePermission()
    {
        $superAdmin = new SuperAdmin();

        auth()->login($superAdmin->user);

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
    }

    public function testModuleHasReadWritePermission()
    {
        $admin = new Admin();

        auth()->login($admin->user);

        foreach ($admin->readRole->modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }
        foreach ($admin->writeRole->modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
    }
}
