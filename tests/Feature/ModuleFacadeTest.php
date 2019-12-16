<?php

namespace Lararole\Tests\Feature;

use Lararole\Tests\TestCase;

class ModuleFacadeTest extends TestCase
{
    public function testAllModuleFacade()
    {
        $this->artisan('migrate:modules');

        $this->assertEquals(['Product', 'Inventory', 'Product Listing', 'Brand', 'User Management', 'User', 'Role', 'Order Processing', 'New Orders', 'Dispatched', 'Settings'], module()->all()->pluck('name')->toArray());
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