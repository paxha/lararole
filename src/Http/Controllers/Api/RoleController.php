<?php

namespace Lararole\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Lararole\Http\Resources\RoleCollection;
use Lararole\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'roles' => new RoleCollection(Role::orderByDesc('id')->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:modules'],
            'modules' => ['nullable', 'array'],
            'modules.*.id' => ['nullable', 'exists:modules,id'],
            'modules.*.permission' => ['nullable', 'in:read,write'],
        ]);

        $trashedRole = Role::onlyTrashed()->whereName($request->name)->first();

        if ($trashedRole) {
            $trashedRole->restore();
            $trashedRole->update($request->all());

            return response()->json([
                'message' => $trashedRole->name . ' successfully restored.',
            ]);
        }

        $role = Role::create($request->all());

        return response()->json([
            'message' => $role->name . ' successfully created.',
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Role $role)
    {
        return response()->json([
            'role' => new \Lararole\Http\Resources\Role($role),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:modules'],
            'modules' => ['nullable', 'array'],
            'modules.*.id' => ['nullable', 'exists:modules,id'],
            'modules.*.permission' => ['nullable', 'in:read,write'],
        ]);

        $role->update($request->all());

        return response()->json([
            'message' => $role->name . ' successfully updated.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $name = $role->name;

        $role->delete();

        return response()->json([
            'message' => $name . ' successfully deleted.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMany(Request $request)
    {
        $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*.id' => ['required', 'exists:roles,id'],
        ]);

        Role::destroy($request->roles);

        return response()->json([
            'message' => 'Roles successfully deleted.',
        ]);
    }
}
