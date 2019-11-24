<?php


namespace Lararole\Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Lararole\Database\Seeds\LararoleSeeder;
use Lararole\Models\Module;
use Lararole\Models\Role;
use Lararole\Tests\Models\User;
use Lararole\Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function testCase()
    {
        $this->artisan('db:seed', ['--class' => LararoleSeeder::class]);

        factory(User::class, 10)->create();

        User::all()->each(function ($user) {
            $user->roles()->attach(Role::all()->random(rand(1, 3))->pluck('id')->toArray());
        });

        $this->assertNotEmpty(Module::all(), 'Modules data not be empty');
        $this->assertNotEmpty(Role::all(), 'Modules data not be empty');
        $this->assertNotEmpty(Role::all()->random()->modules, 'Role modules data not be empty');
        $this->assertNotEmpty(User::all(), 'Users data not be empty');
        $this->assertNotEmpty(Role::all()->random()->users, 'Role user data not be empty');
        $this->assertNotEmpty(User::all()->random()->roles, 'User role data not be empty');
        $this->assertNotEmpty(User::all()->random()->modules, 'User modules data not be empty');
    }
}