<?php

namespace Lararole\Tests\Feature;

use Lararole\Models\Module;
use Lararole\Tests\TestCase;

class ModuleTest extends TestCase
{
    public function testUsers()
    {
        /*Product Users*/
        $product_users = Module::whereSlug('product')->first()->users;

        $this->assertEquals(['Super Admin', 'Admin', 'Product Admin'], $product_users->pluck('name')->toArray());

        $this->assertEquals('write', $product_users->where('name', 'Super Admin')->first()->permission->permission);
        $this->assertEquals('read', $product_users->where('name', 'Admin')->first()->permission->permission);
        $this->assertEquals('write', $product_users->where('name', 'Product Admin')->first()->permission->permission);

        /*Inventory Users*/
        $inventory_user = Module::whereSlug('inventory')->first()->users;

        $this->assertEquals(['Super Admin', 'Admin', 'Product Admin', 'Product Editor'], $inventory_user->pluck('name')->toArray());

        $this->assertEquals('write', $inventory_user->where('name', 'Super Admin')->first()->permission->permission);
        $this->assertEquals('read', $inventory_user->where('name', 'Admin')->first()->permission->permission);
        $this->assertEquals('write', $inventory_user->where('name', 'Product Admin')->first()->permission->permission);
        $this->assertEquals('write', $inventory_user->where('name', 'Product Editor')->first()->permission->permission);

        /*Order Processing Users*/
        $order_processing_users = Module::whereSlug('order_processing')->first()->users;

        $this->assertEquals(['Super Admin', 'Admin', 'Order Manager'], $order_processing_users->pluck('name')->toArray());

        $this->assertEquals('write', $order_processing_users->where('name', 'Super Admin')->first()->permission->permission);
        $this->assertEquals('read', $order_processing_users->where('name', 'Admin')->first()->permission->permission);
        $this->assertEquals('write', $order_processing_users->where('name', 'Order Manager')->first()->permission->permission);

        /*User Management Users*/
        $user_management_users = Module::whereSlug('user_management')->first()->users;

        $this->assertEquals(['Super Admin', 'Admin'], $user_management_users->pluck('name')->toArray());

        $this->assertEquals('write', $user_management_users->where('name', 'Super Admin')->first()->permission->permission);
        $this->assertEquals('write', $user_management_users->where('name', 'Admin')->first()->permission->permission);
    }
}
