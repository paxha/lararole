<?php

namespace Lararole\Models;

use Sluggable\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use RecursiveRelationships\Traits\HasRecursiveRelationships;

class Module extends Model
{
    use HasRecursiveRelationships, HasRelationships, Sluggable;

    protected $fillable = [
        'name', 'alias', 'icon',
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($model) {
            $model->children()->delete();
        });
    }

    public static function separator(): string
    {
        return '_';
    }

    public function getParentKeyName()
    {
        return 'module_id';
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
        return $this->belongsToMany(Role::class)->withPivot('permission')->as('permission')->withTimestamps();
    }

    public function user()
    {
        return $this->users->where('id', auth()->user()->id)->first();
    }
}
