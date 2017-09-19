<?php

Route::group(['middleware' => 'web', 'prefix' => 'teammanagment', 'namespace' => 'Modules\TeamManagment\Http\Controllers'], function()
{
    Route::middleware('auth')->group(function () {

    });
});
