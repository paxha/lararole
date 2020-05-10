<?php

namespace Lararole\Http\Controllers\Api;

use Lararole\Models\Module;
use Lararole\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Lararole\Http\Resources\RoleCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if ($this->user()->cant('viewAny', Role::class)) {
            throw new HttpException(403, 'Access Denied!');
        }

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
        if ($this->user()->cant('create', Role::class)) {
            throw new HttpException(403, 'Access Denied!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'modules' => ['required', 'array'],
            'modules.*.module_id' => ['required', 'exists:modules,id'],
            'modules.*.permission' => ['required', 'in:read,write'],
        ]);

        $trashedRole = Role::onlyTrashed()->whereName($request->name)->first();

        if ($trashedRole) {
            DB::beginTransaction();
            try {
                if ($this->user()->cant('restore', $trashedRole)) {
                    throw new HttpException(403, 'Access Denied!');
                }

                $trashedRole->restore();

                $trashedRole->update($request->all());

                $trashedRole->modules()->sync($request->modules);

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();

                return response()->json([
                    'message' => $exception->getMessage(),
                ], 500);
            }

            return response()->json([
                'message' => $trashedRole->name . ' successfully restored.',
            ]);
        }

        DB::beginTransaction();
        try {
            $role = Role::create($request->all());

            $role->modules()->attach($request->modules);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }

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
        if ($this->user()->cant('update', $role)) {
            throw new HttpException(403, 'Access Denied!');
        }

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
        if ($this->user()->cant('update', $role)) {
            throw new HttpException(403, 'Access Denied!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'modules' => ['required', 'array'],
            'modules.*.module_id' => ['required', 'exists:modules,id'],
            'modules.*.permission' => ['required', 'in:read,write'],
        ]);

        DB::beginTransaction();
        try {
            $role->update($request->all());

            $role->modules()->detach();
            $role->modules()->attach($request->modules);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }

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
        if ($this->user()->cant('delete', $role)) {
            throw new HttpException(403, 'Access Denied!');
        }

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
        if ($this->user()->cant('deleteMany', Role::class)) {
            throw new HttpException(403, 'Access Denied!');
        }

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
