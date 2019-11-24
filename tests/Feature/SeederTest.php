<?php

namespace Lararole\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Lararole\Models\Module;
use Lararole\Models\Role;
use Lararole\Tests\Models\User;
use Lararole\Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function testCase()
    {
        foreach (config('lararole.modules') as $module) {
            $m = Module::create([
                'name' => $module['name'],
                'icon' => @$module['icon']
            ]);

            if (@$module['modules']) {
                $m->create_modules(@$module['modules']);
            }
        }

        Role::create(['name' => 'Super Admin'])->modules()->attach(Module::isRoot()->get()->pluck('id'), ['permission' => 'write']);

        factory(Role::class, 10)->create();

        Role::where('slug', '!=', 'super_admin')->get()->each(function ($role) {
            $role->modules()->attach(Module::isRoot()->get()->random(rand(1, 3))->pluck('id')->toArray());
        });

        factory(User::class, 10)->create()->each(function ($user) {
            $user->roles()->attach(Role::all()->random(rand(1, 3))->pluck('id')->toArray());
        });

        $this->assertNotEmpty(Module::all(), 'Modules data not be empty');
        $this->assertNotEmpty(Role::all(), 'Modules data not be empty');
        $this->assertNotEmpty(Role::all()->random()->modules, 'Role modules data not be empty');
        $this->assertNotEmpty(User::all(), 'Users data not be empty');
        $this->assertNotEmpty(User::all()->random()->roles, 'User role data not be empty');
        $this->assertNotEmpty(User::all()->random()->modules, 'User modules data not be empty');
    }
}