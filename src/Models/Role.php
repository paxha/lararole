<?php

namespace Lararole\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
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
