<?php

namespace Lararole\Policies;

use Lararole\Models\Module;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
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
     * Determine whether the user can view the model.
     *
     * @param $user
     * @param Module $module
     * @return mixed
     */
    public function view($user, Module $module)
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
     * @param Module $module
     * @return mixed
     */
    public function update($user, Module $module)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can toggle active the model.
     *
     * @param $user
     * @param Module $module
     * @return mixed
     */
    public function toggleActive($user, Module $module)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param $user
     * @param Module $module
     * @return mixed
     */
    public function delete($user, Module $module)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param $user
     * @param Module $module
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
     * @param Module $module
     * @return mixed
     */
    public function restore($user, Module $module)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param $user
     * @param Module $module
     * @return mixed
     */
    public function forceDelete($user, Module $module)
    {
        return $user->isSuperAdmin();
    }
}
