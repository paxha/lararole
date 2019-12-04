<?php

namespace Lararole\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Module extends Model
{
    use HasRecursiveRelationships, HasRelationships;

    protected $fillable = [
        'name', 'icon',
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
            foreach ($model->modules as $module) {
                $module->delete();
            }
        });
    }

    public function getParentKeyName()
    {
        return 'module_id';
    }

    public function create_modules(array $modules)
    {
        foreach ($modules as $module) {
            $sub_module = $this->modules()->create([
                'name' => $module['name'],
                'icon' => @$module['icon'],
            ]);

            if (@$module['modules']) {
                $sub_module->create_modules($module['modules']);
            }
        }
    }

    public function modules()
    {
        return $this->hasMany(self::class);
    }

    public function users()
    {
        return $this->hasManyDeep(config('lararole.providers.users.model'), ['module_role', Role::class, 'role_user'])->withPivot('module_role', ['permission'], ModuleRole::class, 'permission');
    }

    public function module_users()
    {
        $module_users = [];
        $users = $this->ancestorsAndSelf()->with('users')->get()->map(function ($module) {
            return $module->users;
        });

        foreach ($users as $user) {
            foreach ($user as $item) {
                $module_users[] = $item;
            }
        }

        return $module_users;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('permission')->as('permission')->withTimestamps();
    }

    public function user_has_permission()
    {
        return auth()->user()->modules()->whereHas('descendantsAndSelf', function ($query) {
            $query->whereIn('id', $this->ancestorsAndSelf()->pluck('id'));
        })->first();
    }
}
