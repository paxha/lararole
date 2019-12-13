<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
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
        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 401);
        }
    }

    public function testModuleHasWritePermissionWithNoRole()
    {
        auth()->login($this->admin);

        $this->admin->roles()->detach();

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }
    }

    public function testModuleHasWritePermission()
    {
        /*Super Admin Test*/
        auth()->login($this->super_admin);

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }

        /*Admin Write Test*/
        auth()->login($this->admin);

        foreach ($this->admin_write_modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->admin_write_modules->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }

        /*Product Admin Test*/
        auth()->login($this->product_admin);

        foreach ($this->product_admin_module as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->product_admin_module->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }

        /*Product Editor Test*/
        auth()->login($this->product_editor);

        foreach ($this->product_editor_modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->product_editor_modules->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }

        /*Order Manager Test*/
        auth()->login($this->order_manager);

        foreach ($this->order_manager_modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->order_manager_modules->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }
    }
}
