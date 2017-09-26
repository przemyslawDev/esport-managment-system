<?php

Route::group(['middleware' => 'web', 'prefix' => 'teammanagment', 'namespace' => 'Modules\Teammanagment\Http\Controllers'], function()
{
    Route::middleware('auth')->group(function () {
        Route::prefix('games')->middleware('role:system_admin|manager')->group(function () {
            Route::get('/', 'GameController@index')->name('teammanagment.games.index');
            Route::get('/{id}', 'GameController@show')->name('teammanagment.games.show');

            Route::get('/game/{id}', 'GameController@get')->name('teammanagment.games.get');
            Route::get('/get/all', 'GameController@getAll')->name('teammanagment.games.get-all');            
        });
        
        Route::prefix('teams')->middleware('role:system_admin|manager')->group(function () {
            Route::get('/', 'TeamController@index')->name('teammanagment.teams.index');
            Route::get('/create', 'TeamController@create')->name('teammanagment.teams.create');
            Route::get('/{id}', 'TeamController@show')->name('teammanagment.teams.show');
            Route::post('/', 'TeamController@store')->name('teammanagment.teams.store');
            Route::get('/{id}/edit', 'TeamController@edit')->name('teammanagment.teams.edit');
            Route::put('/{id}', 'TeamController@update')->name('teammanagment.teams.update');
            Route::delete('/{id}', 'TeamController@destroy')->name('teammanagment.teams.delete');
                
            Route::get('/team/{id}', 'TeamController@get')->name('teammanagment.teams.get');
            Route::get('/get/all', 'TeamController@getAll')->name('teammanagment.teams.get-all');
            Route::get('/{team_id}/games/{game_id}/attach', 'TeamController@attachGame')->name('teammanagment.teams.attach-game');
            Route::get('/{team_id}/games/{game_id}/detach', 'TeamController@detachGame')->name('teammanagment.teams.detach-game');            
        });
    });
});
