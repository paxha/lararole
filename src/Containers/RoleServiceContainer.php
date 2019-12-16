<?php

namespace Lararole\Containers;

use Illuminate\Http\Request;
use Lararole\Models\Module;
use Lararole\Models\Role;

class RoleServiceContainer
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:191'],
            'modules' => ['required', 'array', 'min:1'],
            'modules.*.module_id' => ['required', 'exists:modules,id'],
            'modules.*.permission' => ['required', 'in:read,write'],
        ]);

        $role = Role::create($request->all());
        foreach ($request->modules as $module) {
            $m = Module::find($module['module_id']);
            $role->modules()->attach($m, ['permission' => $module['permission']]);
        }

        return $role;
    }

    public function removeModule(Request $request){
        $request->validate([
            'modules' => ['required', 'array', 'min:1'],
            'modules.*.module_id' => ['required', 'exists:modules,id'],
        ]);


    }
}