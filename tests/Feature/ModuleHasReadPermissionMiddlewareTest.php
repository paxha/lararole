<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Tests\TestCase;
use Lararole\Tests\Helper\Helper;
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
        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 401);
        }
    }

    public function testModuleHasReadPermissionWithNoRole()
    {
        auth()->login($this->admin);

        $this->admin->roles()->detach();

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }
    }

    public function testModuleHasReadPermission()
    {
        /*Super Admin Test*/
        auth()->login($this->super_admin);

        foreach (Module::all() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }

        /*Admin Write Test*/
        auth()->login($this->admin);

        foreach ($this->admin_read_modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
        foreach ($this->admin_write_modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->admin_read_modules->pluck('id')->toArray())->whereNotIn('id', $this->admin_write_modules->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }

        /*Product Admin Test*/
        auth()->login($this->product_admin);

        foreach ($this->product_admin_module as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->product_admin_module->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }

        /*Product Editor Test*/
        auth()->login($this->product_editor);

        foreach ($this->product_editor_modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->product_editor_modules->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }

        /*Order Manager Test*/
        auth()->login($this->order_manager);

        foreach ($this->order_manager_modules as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
        foreach (Module::whereNotIn('id', $this->order_manager_modules->pluck('id')->toArray())->get() as $module) {
            $this->assertEquals(Helper::runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }
    }
}
