<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any?}', 'HomeController@index')->where('any', '.*')->middleware('only-super-admin');
