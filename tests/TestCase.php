<?php

namespace Lararole\Tests;

use Lararole\Tests\Models\User;
use Lararole\LararoleServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->withFactories(__DIR__.'/../src/database/factories');
        $this->withFactories(__DIR__.'/database/factories');

        $this->artisan('vendor:publish', ['--provider' => LararoleServiceProvider::class]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LararoleServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'test');
        $app['config']->set('database.connections.test', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('lararole.providers.users.model', User::class);
    }
}
