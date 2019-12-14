<?php

namespace Lararole\Database\Seeds;

use Faker\Factory;
use Lararole\Models\Role;
use Lararole\Models\Module;
use Illuminate\Database\Seeder;

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
        foreach (config('lararole.modules') as $module) {
            $m = Module::create([
                'name' => $module['name'],
                'icon' => @$module['icon'],
            ]);

            if (@$module['modules']) {
                $m->createModules(@$module['modules']);
            }
        }

        Role::create(['name' => 'Super Admin'])->modules()->attach(Module::root()->get(), ['permission' => 'write']);

        factory(Role::class, 3)->create();

        Role::where('slug', '!=', 'super_admin')->get()->each(function ($role) {
            $faker = Factory::create();
            $role->modules()->attach(Module::root()->get()->random()->pluck('id')->toArray(), ['permission' => $faker->randomElement(['read', 'write'])]);
        });
    }
}
