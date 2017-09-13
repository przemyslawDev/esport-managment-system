<?php

Route::group(['middleware' => 'web', 'prefix' => 'administration', 'namespace' => 'Modules\Administration\Http\Controllers'], function()
{
    Route::prefix('employees')->middleware('role:system_admin|admin')->group(function () {
        Route::get('/', 'EmployeeController@index')->name('administration.employees.index');
        Route::get('/create', 'EmployeeController@create')->name('administration.employees.create');
        Route::get('/{id}', 'EmployeeController@show')->name('administration.employees.show');
        Route::post('/', 'EmployeeController@store')->name('administration.employees.store');
        Route::get('/{id}/edit', 'EmployeeController@edit')->name('administration.employees.edit');
        Route::put('/{id}', 'EmployeeController@update')->name('administration.employees.update');
        Route::delete('/{id}', 'EmployeeController@destroy')->name('administration.employees.delete');
   
        Route::get('/employee/{id}', 'EmployeeController@get')->name('administration.employees.get');
        Route::get('/get/all', 'EmployeeController@getAll')->name('administration.employees.get-all');
    });
});
