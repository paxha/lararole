<?php

namespace Lararole\Tests;

use Lararole\Tests\Models\User;
use Lararole\LararoleServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $modules = [
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
            'name' => 'Settings',
            'icon' => 'icon-settings',
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->withFactories(__DIR__.'/database/factories');
        $this->withFactories(__DIR__.'/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            LararoleServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('lararole.providers.users.model', User::class);

        $app['config']->set('lararole.modules', $this->modules);
    }
}
