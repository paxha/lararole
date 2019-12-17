<?php

namespace Lararole\Traits;

use Lararole\Models\Module;
use Illuminate\Http\Request;

trait HasModules
{
    public function assignModules(Request $request)
    {
        $request->validate([
            'modules' => ['required', 'array', 'min:1'],
            'modules.*.module_id' => ['required', 'exists:modules,id'],
            'modules.*.permission' => ['required', 'in:read,write'],
        ]);

        foreach ($request->modules as $module) {
            $module1 = Module::find($module['module_id']);
            $this->modules()->attach($module1, ['permission' => $module['permission']]);
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
