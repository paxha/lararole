<?php

namespace Lararole\Traits;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Lararole\Models\ModuleRole;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    use HasRelationships;

    /**
     * An admin roles which are assigned to him by super admin or module admin.
     *
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * An admin roles which are assigned to him by super admin or module admin.
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function modules()
    {
        return $this->hasManyDeep(Module::class, ['role_user', Role::class, 'module_role'])->withPivot('module_role', ['permission'], ModuleRole::class, 'permission');
    }

    public function isSuperAdmin()
    {
        return $this->roles()->whereSlug('super-admin')->exists();
    }

    public function assignRoles(array $roles)
    {
        $this->roles()->attach($roles);

        return $this;
    }

    public function assignSuperAdminRole()
    {
        $this->roles()->attach(Role::whereSlug('super-admin')->first());

        return $this;
    }

    public function removeRoles(array $roles)
    {
        $this->roles()->detach($roles);

        return $this;
    }

    public function removeAllRoles()
    {
        $this->roles()->detach();

        return $this;
    }
}
