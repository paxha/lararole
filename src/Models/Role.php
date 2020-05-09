<?php

namespace Lararole\Models;

use Lararole\Traits\Loggable;
use Lararole\Traits\Activable;
use Lararole\Traits\HasModules;
use Sluggable\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

class Role extends Model
{
    use SoftDeletes, Activable, Sluggable, PivotEventTrait, HasModules, Loggable;

    protected $fillable = [
        'name',
    ];

    protected $guarded = [
        'active',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public static function boot()
    {
        parent::boot();

        if (config('lararole.attachAllChildren', false)) {
            self::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
                foreach ($pivotIdsAttributes as $key => $pivotIdsAttribute) {
                    if (Module::find($key)->nestedChildren()->count()) {
                        self::attachAllChildModules($model, $key, @$pivotIdsAttribute['permission']);
                    }
                }
            });

            self::pivotDetached(function ($model, $relationName, $pivotIds) {
                foreach ($pivotIds as $pivotId) {
                    if (Module::find($pivotId)->nestedChildren()->count()) {
                        self::detachAllChildModules($model, $pivotId);
                    }
                }
            });
        }

        self::deleting(function ($model) {
            $model->users()->detach();
        });
    }

    private static function attachAllChildModules($model, $moduleId, $permission)
    {
        $model->modules()->attach(Module::find($moduleId)->nestedChildren, ['permission' => $permission ? $permission : 'read']);
    }

    private static function detachAllChildModules($model, $moduleId)
    {
        $model->modules()->detach(Module::find($moduleId)->nestedChildren);
    }

    public function users()
    {
        return $this->belongsToMany(config('lararole.providers.users.model', \App\User::class))->withTimestamps();
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class)->withPivot('permission')->as('permission')->withTimestamps();
    }
}
