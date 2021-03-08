<?php

namespace Lararole\Database\Seeds;

use Faker\Factory;
use Lararole\Models\Role;
use Lararole\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class LararoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modules
        Artisan::call('migrate:modules');

        Role::create(['name' => 'Super Admin'])->modules()->attach(config('lararole.attachAllChildren') ? Module::root()->get() : Module::all(), ['permission' => 'write']);

        Role::where('slug', '!=', 'super-admin')->get()->each(function ($role) {
            $faker = Factory::create();
            $role->modules()->attach(Module::root()->get()->random()->pluck('id')->toArray(), ['permission' => $faker->randomElement(['read', 'write'])]);
        });
    }
}
