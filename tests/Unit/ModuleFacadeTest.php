<?php

namespace Lararole\Tests\Unit;

use Lararole\Tests\TestCase;

class ModuleFacadeTest extends TestCase
{
    public function testAllModuleFacade()
    {
        $this->artisan('migrate:modules');

        $this->assertCount(11, module()->all());
    }

    public function testFindModuleFacade()
    {
        $this->artisan('migrate:modules');

        $this->assertIsObject(module()->find(1));
    }

    public function testRootModuleFacade()
    {
        $this->artisan('migrate:modules');

        $this->assertCount(4, module()->root());
    }

    public function testLeafModuleFacade()
    {
        $this->artisan('migrate:modules');

        $this->assertCount(7, module()->leaf());
    }
}
