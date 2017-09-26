<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/403', function () {
    return view('403');
})->name('403');

Route::get('/account/verify/{code}', 'Auth\RegisterController@confirm');

Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login.r');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    
    Route::prefix('users')->middleware('role:system_admin')->group(function () {
        Route::get('/', 'UserController@index')->name('users.index');
        Route::get('/create', 'UserController@create')->name('users.create');
        Route::get('/{id}', 'UserController@show')->name('users.show');
        Route::post('/', 'UserController@store')->name('users.store');
        Route::get('/{id}/edit', 'UserController@edit')->name('users.edit');
        Route::put('/{id}', 'UserController@update')->name('users.update');
        Route::delete('/{id}', 'UserController@destroy')->name('users.delete');
        
        Route::get('/activate/{id}', 'UserController@activate')->name('users.activate');
        Route::get('/password/reset/{id}', 'UserController@resetPassword')->name('users.reset.password');

        Route::get('/user/{id}', 'UserController@get')->name('users.get');
        Route::get('/get/all', 'UserController@getAll')->name('users.get-all');
    });

    Route::prefix('roles')->middleware('role:system_admin')->group(function () {
        Route::get('/get/all', 'RoleController@getAll')->name('roles.get-all');
    });
});
