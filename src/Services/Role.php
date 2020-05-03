<?php

namespace Lararole\Services;

use Lararole\Models\Module;

class Role
{
    public function superAdminRole()
    {
        return \Lararole\Models\Role::whereSlug('super-admin')->first();
    }

    public function hasSuperAdminRole()
    {
        return $this->superAdminRole() !== null;
    }

    public function syncSuperAdminRoleModules(){
        $this->superAdminRole()->modules()->sync(config('lararole.attachAllChildren') ? Module::root()->get() : Module::all(), ['permission' => 'write']);
    }
}
