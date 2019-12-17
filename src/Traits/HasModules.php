<?php

namespace Lararole\Traits;

use Lararole\Models\Module;

trait HasModules
{
    public function assignModules(array $modules, array $permissions = null)
    {
        foreach ($modules as $index => $module) {
            $module1 = Module::find($module);
            $this->modules()->attach($module1, ['permission' => @$permissions[$index] ? $permissions[$index] : 'read']);
        }

        return $this;
    }

    public function removeModules(array $modules)
    {
        foreach ($modules as $module) {
            $this->modules()->detach($module);
        }

        return $this;
    }

    public function removeAllModules()
    {
        $this->modules()->detach();

        return $this;
    }
}
