<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Tests\Models\User;
use Lararole\Tests\TestCase;

class ModuleTest extends TestCase
{
    public function testModuleUsers()
    {
        $modules = Module::whereHas('users')->get();

        $this->assertNotEmpty($modules);

        foreach ($modules as $module) {
            foreach (Module::where('slug', $module->slug)->first()->module_users() as $module_user) {
                $this->assertNotEmpty(User::class, $module_user);
            }
        }
    }
}
