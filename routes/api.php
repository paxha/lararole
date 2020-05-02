<?php

use Illuminate\Support\Facades\Route;

Route::get('/modules', 'ModuleController@index');
Route::post('/module/create', 'ModuleController@store');
Route::post('/module/{module}/create', 'ModuleController@storeChild');
Route::get('/module/{module}/edit', 'ModuleController@edit');
Route::put('/module/{module}/update', 'ModuleController@update');
Route::delete('/module/{module}/delete', 'ModuleController@destroy');
Route::delete('/modules/delete', 'ModuleController@destroyMany');
