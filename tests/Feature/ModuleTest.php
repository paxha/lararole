<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Tests\TestCase;

class ModuleTest extends TestCase
{
    public function testModuleUsers()
    {
        $modules = Module::whereHas('users')->get();

        $this->assertNotEmpty($modules);

        foreach ($modules as $module) {
            $this->assertNotEmpty(Module::where('slug', $module->slug)->first()->module_users());
        }
    }
}
