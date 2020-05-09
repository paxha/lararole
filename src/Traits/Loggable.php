<?php

namespace Lararole\Traits;

trait Loggable
{
    public static function bootLoggable()
    {
        if (config('lararole.loggable', false)) {
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
                if (auth()->check()) {
                    $user = auth()->user();
                    $model->deleted_by = $user->id;
                    $model->save();
                }
            });
        }
    }

    public function creator()
    {
        return $this->belongsTo(config('lararole.providers.users.model', \App\User::class), 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(config('lararole.providers.users.model', \App\User::class), 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(config('lararole.providers.users.model', \App\User::class), 'deleted_by');
    }
}
