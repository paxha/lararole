<?php

namespace Lararole\Tests\Unit;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Tests\TestCase;
use Lararole\Tests\Models\User;
use Illuminate\Support\Facades\Config;

class CommandTest extends TestCase
{
    private $moduleViews = [
        'modules.product.inventory.product_listing',
        'modules.product.brand',
        'modules.user_management.user_1',
        'modules.user_management.role',
        'modules.order_processing.new_orders',
        'modules.order_processing.dispatched',
        'modules.others',
    ];

    private $excludeModuleViews = [
        'modules.product',
        'modules.product.inventory',
        'modules.user_management',
        'modules.order_processing',
    ];

    private $newModules = [
        [
            'name' => 'Product',
            'icon' => 'icon-product',
            'modules' => [
                [
                    'name' => 'Inventory',
                    'modules' => [
                        ['name' => 'Product Listing'],
                    ],
                ],
                ['name' => 'Brand'],
                ['name' => 'Supplier'],
            ],
        ],
        [
            'name' => 'User Management',
            'icon' => 'icon-user',
            'modules' => [
                [
                    'name' => 'User',
                    'icon' => 'icon-user',
                ],
                [
                    'name' => 'Role',
                    'icon' => 'icon-role',
                ],
            ],
        ],
        [
            'name' => 'Order Processing',
            'icon' => 'icon-order',
            'modules' => [
                ['name' => 'New Orders'],
                ['name' => 'Dispatched'],
            ],
        ],
        [
            'name' => 'Others',
            'icon' => 'icon-others',
        ],
    ];

    public function testMigrateModulesCommand()
    {
        $this->artisan('migrate:modules');

        $this->assertCount(11, Module::all());
    }

    public function testMigrateModulesWithSequenceCommand()
    {
        $this->artisan('migrate:modules');

        $this->assertDatabaseHas('modules', [
            'name' => 'Product',
            'sequence' => 1,
        ])->assertDatabaseHas('modules', [
            'name' => 'Inventory',
            'sequence' => 2,
        ])->assertDatabaseHas('modules', [
            'name' => 'Product Listing',
            'sequence' => 3,
        ])->assertDatabaseHas('modules', [
            'name' => 'Brand',
            'sequence' => 4,
        ])->assertDatabaseHas('modules', [
            'name' => 'User Management',
            'sequence' => 5,
        ])->assertDatabaseHas('modules', [
            'name' => 'User',
            'sequence' => 6,
        ])->assertDatabaseHas('modules', [
            'name' => 'Role',
            'sequence' => 7,
        ])->assertDatabaseHas('modules', [
            'name' => 'Order Processing',
            'sequence' => 8,
        ])->assertDatabaseHas('modules', [
            'name' => 'New Orders',
            'sequence' => 9,
        ])->assertDatabaseHas('modules', [
            'name' => 'Dispatched',
            'sequence' => 10,
        ])->assertDatabaseHas('modules', [
            'name' => 'Others',
            'sequence' => 11,
        ]);
    }

    public function testMigrateModulesWithSyncCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        Config::set('lararole.modules', $this->newModules);

        $this->artisan('migrate:modules --sync');

        $this->assertEquals(12, Module::whereSlug('supplier')->first()->id);

        $superAdminRole = Role::whereSlug('super-admin')->first();

        $this->assertCount(12, $superAdminRole->modules);
    }

    public function testMigrateModulesWithSyncAndSequenceCommand()
    {
        $localModules = [
            [
                'name' => 'User Management',
                'icon' => 'icon-user',
                'modules' => [
                    [
                        'name' => 'User',
                        'icon' => 'icon-user',
                    ],
                    [
                        'name' => 'Role',
                        'icon' => 'icon-role',
                    ],
                ],
            ],
            [
                'name' => 'Product',
                'icon' => 'icon-product',
                'modules' => [
                    ['name' => 'Brand'],
                    [
                        'name' => 'Inventory',
                        'modules' => [
                            ['name' => 'Product Listing'],
                        ],
                    ],
                    ['name' => 'Supplier'],
                ],
            ],
            [
                'name' => 'Order Processing',
                'icon' => 'icon-order',
                'modules' => [
                    ['name' => 'New Orders'],
                    ['name' => 'Dispatched'],
                ],
            ],
            [
                'name' => 'Others',
                'icon' => 'icon-others',
            ],
        ];

        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        Config::set('lararole.modules', $this->newModules);

        $this->artisan('migrate:modules --sync');

        $this->assertDatabaseHas('modules', [
            'name' => 'Product',
            'sequence' => 1,
        ])->assertDatabaseHas('modules', [
            'name' => 'Inventory',
            'sequence' => 2,
        ])->assertDatabaseHas('modules', [
            'name' => 'Product Listing',
            'sequence' => 3,
        ])->assertDatabaseHas('modules', [
            'name' => 'Brand',
            'sequence' => 4,
        ])->assertDatabaseHas('modules', [
            'name' => 'Supplier',
            'sequence' => 5,
        ])->assertDatabaseHas('modules', [
            'name' => 'User Management',
            'sequence' => 6,
        ])->assertDatabaseHas('modules', [
            'name' => 'User',
            'sequence' => 7,
        ])->assertDatabaseHas('modules', [
            'name' => 'Role',
            'sequence' => 8,
        ])->assertDatabaseHas('modules', [
            'name' => 'Order Processing',
            'sequence' => 9,
        ])->assertDatabaseHas('modules', [
            'name' => 'New Orders',
            'sequence' => 10,
        ])->assertDatabaseHas('modules', [
            'name' => 'Dispatched',
            'sequence' => 11,
        ])->assertDatabaseHas('modules', [
            'name' => 'Others',
            'sequence' => 12,
        ]);


        Config::set('lararole.modules', $localModules);

        $this->artisan('migrate:modules --sync');

        $this->assertDatabaseHas('modules', [
            'name' => 'User Management',
            'sequence' => 1,
        ])->assertDatabaseHas('modules', [
            'name' => 'User',
            'sequence' => 2,
        ])->assertDatabaseHas('modules', [
            'name' => 'Role',
            'sequence' => 3,
        ])->assertDatabaseHas('modules', [
            'name' => 'Product',
            'sequence' => 4,
        ])->assertDatabaseHas('modules', [
            'name' => 'Brand',
            'sequence' => 5,
        ])->assertDatabaseHas('modules', [
            'name' => 'Inventory',
            'sequence' => 6,
        ])->assertDatabaseHas('modules', [
            'name' => 'Product Listing',
            'sequence' => 7,
        ])->assertDatabaseHas('modules', [
            'name' => 'Supplier',
            'sequence' => 8,
        ])->assertDatabaseHas('modules', [
            'name' => 'Order Processing',
            'sequence' => 9,
        ])->assertDatabaseHas('modules', [
            'name' => 'New Orders',
            'sequence' => 10,
        ])->assertDatabaseHas('modules', [
            'name' => 'Dispatched',
            'sequence' => 11,
        ])->assertDatabaseHas('modules', [
            'name' => 'Others',
            'sequence' => 12,
        ]);
    }

    public function testMakeViewsCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:views');

        foreach ($this->moduleViews as $moduleView) {
            $this->assertTrue(view()->exists($moduleView.'.create'));
            $this->assertTrue(view()->exists($moduleView.'.edit'));
            $this->assertTrue(view()->exists($moduleView.'.index'));
            $this->assertTrue(view()->exists($moduleView.'.show'));
        }

        foreach ($this->excludeModuleViews as $excludeModuleView) {
            $this->assertFalse(view()->exists($excludeModuleView.'.create'));
            $this->assertFalse(view()->exists($excludeModuleView.'.edit'));
            $this->assertFalse(view()->exists($excludeModuleView.'.index'));
            $this->assertFalse(view()->exists($excludeModuleView.'.show'));
        }
    }

    public function testMakeSuperAdminRoleCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        $this->assertDatabaseHas('roles', [
            'name' => 'Super Admin',
            'slug' => 'super-admin',
        ]);

        $superAdminRole = Role::whereSlug('super-admin')->first();

        $this->assertCount(11, $superAdminRole->modules);
    }

    public function testAssignSuperAdminRoleCommand()
    {
        $this->artisan('migrate:modules');
        $this->artisan('make:super-admin-role');

        $user = User::create([
            'name' => 'Super Admin',
        ]);

        $this->artisan('assign-super-admin-role --user='.$user->id);

        $this->assertCount(1, $user->roles);
    }

    public function testDBSeed()
    {
        $this->artisan('db:seed', ['--class' => '\Lararole\Database\Seeds\LararoleSeeder']);

        $this->assertCount(11, Module::all());

        $this->assertDatabaseHas('roles', [
            'name' => 'Super Admin',
            'slug' => 'super-admin',
        ]);

        $superAdminRole = Role::whereSlug('super-admin')->first();

        $this->assertCount(11, $superAdminRole->modules);

        $this->assertCount(4, Role::all());
    }
}
