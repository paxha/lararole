<?php

namespace Lararole\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lararole\Models\Module;
use Lararole\Models\ModuleRole;
use Lararole\Models\Role;
use Paxha\HasManyThroughDeep\HasManyThroughDeep;
use Paxha\HasManyThroughDeep\HasRelationships;

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
     * @return HasManyThroughDeep
     */
    public function modules()
    {
        return $this->hasManyThroughDeep(Module::class, ['role_user', Role::class, 'module_role'])->withPivot('module_role', ['permission'], ModuleRole::class, 'permission');
    }
}
