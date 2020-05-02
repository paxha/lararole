<?php

use Illuminate\Support\Facades\Route;

Route::prefix('lararole')->group(function () {
    Route::get('/{any?}', 'HomeController@index')->where('any', '.*');
});
