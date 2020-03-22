<?php

namespace Lararole\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use RecursiveRelationships\Traits\HasRecursiveRelationships;

class Module extends Model
{
    use HasRecursiveRelationships, HasRelationships;

    protected $fillable = [
        'name', 'alias', 'icon',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->slug = Str::slug($model->name, '_');

            $latestSlug = self::whereRaw("slug = '$model->slug'")->latest('id')->value('slug');
            if ($latestSlug) {
                $pieces = explode('_', $latestSlug);
                $number = intval(end($pieces));
                $model->slug .= '_'.($number + 1);
            }
        });

        self::deleting(function ($model) {
            $model->children()->delete();
        });
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
                'name' => $module['name']
            ], [
                'icon' => @$module['icon'],
                'alias' => @$module['alias'] ?? $module['name']
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
