<?php

namespace Lararole\Models;

use Lararole\Traits\Loggable;
use Lararole\Traits\Activable;
use Sluggable\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use RecursiveRelationships\Traits\HasRecursiveRelationships;

class Module extends Model
{
    use SoftDeletes, Activable, Sluggable, HasRecursiveRelationships, HasRelationships, Loggable;

    protected $fillable = [
        'module_id', 'name', 'alias', 'icon',
    ];

    protected $guarded = [
        'active',
    ];

    public static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            if (! $model->active) {
                foreach ($model->children as $child) {
                    $child->active = false;
                    $child->save();
                }
            } else {
                if ($model->parent) {
                    $model->parent->active = true;
                    $model->parent->save();
                }
            }
        });

        self::deleting(function ($model) {
            $model->children()->delete();

            $model->roles()->detach();
        });
    }

    public function getParentKeyName()
    {
        return 'module_id';
    }

    public static function separator(): string
    {
        return '_';
    }

    public function createModules(array $modules)
    {
        foreach ($modules as $module) {
            $subModule = $this->children()->create([
                'name' => $module['name'],
                'icon' => @$module['icon'],
                'alias' => @$module['alias'] ?? $module['name'],
            ]);

            if (@$module['modules']) {
                $subModule->createModules($module['modules']);
            }
        }
    }

    public function updateOrCreateModules(array $modules)
    {
        foreach ($modules as $module) {
            $subModule = $this->children()->updateOrCreate([
                'name' => $module['name'],
            ], [
                'icon' => @$module['icon'],
                'alias' => @$module['alias'] ?? $module['name'],
            ]);

            if (@$module['modules']) {
                $subModule->updateOrCreateModules($module['modules']);
            }
        }
    }

    public function users()
    {
        return $this->hasManyDeep(config('lararole.providers.users.model'), ['module_role', Role::class, 'role_user'])->withPivot('module_role', ['permission'], ModuleRole::class, 'permission');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('permission')->as('permission')->withTimestamps()->whereActive(true);
    }

    public function user()
    {
        return $this->users->where('id', auth()->user()->id)->first();
    }
}
