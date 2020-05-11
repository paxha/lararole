<?php

use Illuminate\Support\Facades\Route;

Route::get('modules', 'ModuleController@index');
Route::get('module-stats', 'ModuleController@stats');
Route::post('module/create', 'ModuleController@store');
Route::get('module/{module}/edit', 'ModuleController@edit');
Route::put('module/{module}/update', 'ModuleController@update');
Route::delete('module/{module}/delete', 'ModuleController@destroy');
Route::delete('modules/delete', 'ModuleController@destroyMany');

Route::get('roles/', 'RoleController@index');
Route::get('role-stats', 'RoleController@stats');
Route::post('role/create', 'RoleController@store');
Route::get('role/{role}/edit', 'RoleController@edit');
Route::put('role/{role}/update', 'RoleController@update');
Route::delete('role/{role}/delete', 'RoleController@destroy');
Route::delete('roles/delete', 'RoleController@destroyMany');

