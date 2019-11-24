<?php

namespace Lararole\Database\Seeds;

use Illuminate\Database\Seeder;
use Lararole\Models\Module;
use Lararole\Models\Role;

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
                $m->create_modules(@$module['modules']);
            }
        }

        $role = Role::create(['name' => 'Super Admin']);
        $role->modules()->attach(Module::isRoot()->get()->pluck('id'), ['permission' => 'write']);

        factory(Role::class, 10)->create();

        Role::where('slug', '!=', 'super_admin')->get()->each(function ($role) {
            $role->modules()->attach(Module::all()->random(rand(0, 3))->pluck('id')->toArray());
        });
    }
}
