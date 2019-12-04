<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Illuminate\Http\Request;
use Lararole\Tests\TestCase;
use Illuminate\Http\Response;
use Lararole\Tests\Models\User;
use Lararole\Http\Middleware\ModuleHasReadPermission;
use Lararole\Http\Middleware\ModuleHasWritePermission;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MiddlewareTest extends TestCase
{
    protected $moduleHasReadPermission;
    protected $moduleHasWritePermission;

    public function setUp(): void
    {
        parent::setUp();

        $this->moduleHasReadPermission = new ModuleHasReadPermission();
        $this->moduleHasWritePermission = new ModuleHasWritePermission();
    }

    public function testModuleHasReadPermissionUnauthenticated()
    {
        foreach (Module::isLeaf()->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 401);
        }
    }

    public function testModuleHasReadPermissionWithNoRole()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        foreach (Module::all() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }
    }

    public function testModuleHasReadPermissionWithOneRootModule()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        $role = Role::create([
            'name' => 'test',
        ]);

        $random_module = Module::isRoot()->get()->random();

        $role->modules()->sync([
            [
                'module_id' => $random_module->id,
                'permission' => 'read',
            ],
        ]);

        $user->roles()->attach($role);

        foreach (Module::isLeaf()->where('module_id', '=', $random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }

        foreach (Module::where('id', '!=', $random_module->id)->where('module_id', '!=', $random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 302, $module.' - '.$random_module);
        }
    }

    public function testModuleHasReadPermissionWithOneRootChildModule()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        $role = Role::create([
            'name' => 'test',
        ]);

        $random_module = Module::hasParent()->get()->random();

        $role->modules()->sync([
            [
                'module_id' => $random_module->id,
                'permission' => 'read',
            ],
        ]);

        $user->roles()->attach($role);

        foreach (Module::whereId($random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }

        foreach (Module::whereModuleId($random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
    }

    public function testModuleHasReadPermissionWithOneRootChildSiblingModule()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        $role = Role::create([
            'name' => 'test',
        ]);

        $random_module = Module::hasParent()->get()->random();

        $role->modules()->sync([
            [
                'module_id' => $random_module->id,
                'permission' => 'read',
            ],
        ]);

        $user->roles()->attach($role);

        $siblings = $random_module->siblings;

        foreach ($siblings as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 302);
        }
    }

    public function testModuleHasReadPermissionWithSuperAdminRole()
    {
        $user = User::all()->random();
        auth()->login($user);

        $this->artisan('make:super-admin-role');
        $role = Role::whereSlug('super_admin')->first();

        $user->roles()->detach();
        $user->roles()->attach($role);

        foreach (Module::all() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasReadPermission, $module->slug), 200);
        }
    }

    public function testModuleHasWritePermissionUnauthenticated()
    {
        foreach (Module::isLeaf()->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 401);
        }
    }

    public function testModuleHasWritePermissionWithNoRole()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        foreach (Module::all() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }
    }

    public function testModuleHasWritePermissionWithOneRootModule()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        $role = Role::create([
            'name' => 'test',
        ]);

        $random_module = Module::isRoot()->get()->random();

        $role->modules()->sync([
            [
                'module_id' => $random_module->id,
                'permission' => 'write',
            ],
        ]);

        $user->roles()->attach($role);

        foreach (Module::isLeaf()->where('module_id', '=', $random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }

        foreach (Module::where('id', '!=', $random_module->id)->where('module_id', '!=', $random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 302, $module.' - '.$random_module);
        }
    }

    public function testModuleHasWritePermissionWithOneRootModuleReadPermission()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        $role = Role::create([
            'name' => 'test',
        ]);

        $random_module = Module::isRoot()->get()->random();

        $role->modules()->sync([
            [
                'module_id' => $random_module->id,
                'permission' => 'read',
            ],
        ]);

        $user->roles()->attach($role);

        foreach (Module::isLeaf()->where('module_id', '=', $random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }

        foreach (Module::where('id', '!=', $random_module->id)->where('module_id', '!=', $random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 302, $module.' - '.$random_module);
        }
    }

    public function testModuleHasWritePermissionWithOneRootChildModule()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        $role = Role::create([
            'name' => 'test',
        ]);

        $random_module = Module::hasParent()->get()->random();

        $role->modules()->sync([
            [
                'module_id' => $random_module->id,
                'permission' => 'write',
            ],
        ]);

        $user->roles()->attach($role);

        foreach (Module::whereId($random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }

        foreach (Module::whereModuleId($random_module->id)->get() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
    }

    public function testModuleHasWritePermissionWithOneRootChildSiblingModule()
    {
        $user = User::all()->random();
        auth()->login($user);

        $user->roles()->detach();

        $role = Role::create([
            'name' => 'test',
        ]);

        $random_module = Module::hasParent()->get()->random();

        $role->modules()->sync([
            [
                'module_id' => $random_module->id,
                'permission' => 'write',
            ],
        ]);

        $user->roles()->attach($role);

        $siblings = $random_module->siblings;

        foreach ($siblings as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 302);
        }
    }

    public function testModuleHasWritePermissionWithSuperAdminRole()
    {
        $user = User::all()->random();
        auth()->login($user);

        $this->artisan('make:super-admin-role');
        $role = Role::whereSlug('super_admin')->first();

        $user->roles()->detach();
        $user->roles()->attach($role);

        foreach (Module::all() as $module) {
            $this->assertEquals($this->runMiddleware($this->moduleHasWritePermission, $module->slug), 200);
        }
    }

    protected function runMiddleware($middleware, $module_slug)
    {
        try {
            $request = new Request();

            $request->merge([
                'module_slug' => $module_slug,
            ]);

            return $middleware->handle($request, function () {
                return (new Response())->setContent('<html lang="en"></html>');
            })->status();
        } catch (HttpException $e) {
            return $e->getStatusCode();
        }
    }
}
