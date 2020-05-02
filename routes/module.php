<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Lararole\Http\Controllers')->group(function () {
    Route::get('access_denied', 'ModuleController@accessDenied')->name('access.denied');

    Route::middleware(['web', 'auth', 'permission.read'])->group(function () {
        Route::get('module/{moduleSlug}', 'ModuleController@index')->name('module.index');
        Route::get('module/{moduleSlug}/create', 'ModuleController@create')->name('module.create');
        Route::get('module/{moduleSlug}/{module}', 'ModuleController@show')->name('module.show');
        Route::get('module/{moduleSlug}/{module}/edit', 'ModuleController@edit')->name('module.edit');
    });
});
