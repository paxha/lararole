<?php

namespace Lararole\Models;

use Lararole\Traits\HasModules;
use Sluggable\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

class Role extends Model
{
    use SoftDeletes, Sluggable, PivotEventTrait, HasModules;

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

        self::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });

        self::updating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                $model->updated_by = $user->id;
            }
        });

        self::deleting(function ($model) {
            $model->users()->detach();
            if (auth()->check()) {
                $user = auth()->user();
                $model->deleted_by = $user->id;
                $model->save();
            }
        });

        self::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            if (config('lararole.attach_all_children')) {
                foreach ($pivotIdsAttributes as $key => $pivotIdsAttribute) {
                    if (Module::find($key)->nestedChildren()->count()) {
                        self::attachAllChildModules($model, $key, @$pivotIdsAttribute['permission']);
                    }
                }
            }
        });

        self::pivotDetached(function ($model, $relationName, $pivotIds) {
            if (config('lararole.attach_all_children')) {
                foreach ($pivotIds as $pivotId) {
                    if (Module::find($pivotId)->nestedChildren()->count()) {
                        self::detachAllChildModules($model, $pivotId);
                    }
                }
            }
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

    public function markAsActive()
    {
        $this->active = true;
        $this->save();
    }

    public function markAsInactive()
    {
        $this->active = false;
        $this->save();
    }

    public function users()
    {
        return $this->belongsToMany(config('lararole.providers.users.model'))->withTimestamps();
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class)->withPivot('permission')->as('permission')->withTimestamps();
    }
}
