<?php

namespace Lararole\Tests\Helper;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Tests\Models\User;
use Illuminate\Support\Facades\Artisan;

class Admin
{
    public $user, $readRole, $writeRole, $readModule, $writeModule;

    public function __construct()
    {
        Artisan::call('migrate:modules');

        $this->readRole = Role::create([
            'name' => 'Read Role',
        ]);
        $this->writeRole = Role::create([
            'name' => 'Write Role',
        ]);

        $this->readModule = Module::root()->whereSlug('product')->first();
        $this->writeModule = Module::root()->whereSlug('user_management')->first();

        $this->readRole->modules()->attach($this->readModule, ['permission' => 'read']);
        $this->writeRole->modules()->attach($this->writeModule, ['permission' => 'write']);

        $this->user = User::create([
            'name' => 'Admin',
        ]);

        $this->user->roles()->attach($this->readRole);
        $this->user->roles()->attach($this->writeRole);
    }
}
