<?php

namespace Lararole\Policies;

use Lararole\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param $user
     * @return mixed
     */
    public function viewAny($user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view stats models.
     *
     * @param $user
     * @return mixed
     */
    public function stats($user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param $user
     * @param Role $role
     * @return mixed
     */
    public function view($user, Role $role)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param $user
     * @param Role $role
     * @return mixed
     */
    public function update($user, Role $role)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param $user
     * @param Role $role
     * @return mixed
     */
    public function delete($user, Role $role)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param $user
     * @param Role $role
     * @return mixed
     */
    public function deleteMany($user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param $user
     * @param Role $role
     * @return mixed
     */
    public function restore($user, Role $role)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param $user
     * @param Role $role
     * @return mixed
     */
    public function forceDelete($user, Role $role)
    {
        return $user->isSuperAdmin();
    }
}
