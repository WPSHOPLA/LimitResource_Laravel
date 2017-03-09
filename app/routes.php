<?php

Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@auth');
Route::get('/logout', 'AuthController@logout');
Route::get('/signup', 'AuthController@signup');
Route::post('/register', 'AuthController@register');
Route::controller('password', 'RemindersController');

Route::group(['before' => 'auth'], function(){
	Route::get('/', 'DashboardController@index');

	//MAP
	Route::get('/map', 'MapController@index');
	Route::get('/tasting_planner', 'MapController@tasting_planner');
	Route::get('/vineyard_details/{id}', 'MapController@vineyard_details');

	//USERS
	Route::get('user/profile', 'UserController@profile');
	Route::put('user/{id}', 'UserController@update');
	Route::get('user/toConfirm', 'UserController@toConfirm');
	Route::get('user/getList', 'UserController@getUserList');
	Route::get('user/settings', 'UserController@settings');
	Route::post('user/settings', 'UserController@updateSettings');
	
	Route::group(['before' => 'admin_access'], function(){
		Route::resource('user', 'UserController');
	});
	
	Route::resource('vineyard', 'VineyardController');

	Route::get('wine/getVineyardList', 'WineController@getVineyardList');
	Route::get('wine/{vineyard_id}', 'WineController@index');
	Route::get('wine/{vineyard_id}/create', 'WineController@create');
	Route::post('wine/{vineyard_id}', 'WineController@store');
	Route::get('wine/{vineyard_id}/{id}/edit', 'WineController@edit');
	Route::put('wine/{vineyard_id}/{id}', 'WineController@update');
	Route::delete('wine/{vineyard_id}/{id}', 'WineController@destroy');

	//ADMIN
	Route::get('admin/settings', 'SettingsController@index');
	Route::post('admin/settings', 'SettingsController@update');
});