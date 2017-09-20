<?php

Route::group(['middleware' => 'web', 'prefix' => 'teammanagment', 'namespace' => 'Modules\TeamManagment\Http\Controllers'], function()
{
    Route::middleware('auth')->group(function () {
        Route::prefix('games')->middleware('role:system_admin')->group(function () {
            Route::get('/', 'GameController@index')->name('teammanagment.games.index');
            Route::get('/{id}', 'GameController@show')->name('teammanagment.games.show');

            Route::get('/game/{id}', 'GameController@get')->name('teammanagment.games.get');
            Route::get('/get/all', 'GameController@getAll')->name('teammanagment.games.get-all');            
        });
    });
});
