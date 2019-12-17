<?php

namespace Lararole\Containers;

use Illuminate\Http\Request;
use Lararole\Models\Module;
use Lararole\Models\Role;

class RoleServiceContainer
{
    public function create($name)
    {
        return Role::create(['name' => $name]);
    }

    public function all()
    {
        return Role::all();
    }

    public function find($id)
    {
        return Role::find($id);
    }

    public function trashed($id = null)
    {
        if ($id) {
            return Role::onlyTrashed()->find($id);
        }

        return Role::onlyTrashed()->get();
    }

    public function withTrashed($id = null)
    {
        if ($id) {
            return Role::withTrashed()->find($id);
        }

        return Role::withTrashed()->get();
    }
}