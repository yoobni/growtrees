<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', [
	'as' => 'index',
	'middleware' => ['guest'],
	'uses' => 'IndexController'
]);

/*
Route::get('reset_pw', function () {
	return view('temp');
});
*/

// related to login
Route::group(['as' => 'session.'], function () {
	Route::post('login', [
		'as' => 'store',
		'uses' => 'Auth\LoginController@login'
	]);
        Route::get('logout', [
		'as' => 'destroy',
		'uses' => 'Auth\LoginController@logout'
	]);
});

// related to user info
Route::group(['as' => 'user.'], function () {
	Route::post('register', [
		'as' => 'store',
		'uses' => 'Auth\RegisterController@register'
	]);
	Route::post('reset_pw', [
		'as' => 'reset_pw',
		'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
	]);
	Route::patch('update_profile', [
		'middleware' => ['auth'],
		'as' => 'update',
		'uses' => 'HomeController@updateUserInfo'
	]);
});

Route::group(['middleware' => ['auth']], function () {
	Route::get('home', [
		'as' => 'home',
		'uses' => 'HomeController@index'
	]);

	Route::get('project_list/{str}', [
		'uses' => 'ProjectController@getList'
	]);
	Route::get('project_info/{projectId}', [
		'uses' => 'ProjectController@getInfo'
	]);
	
	Route::post('join_request/', [
		'uses' => 'ProjectController@joinRequest'
	]);
	Route::post('allow_request/', [
		'uses' => 'ProjectController@allowRequest'
	]);
	Route::post('deny_request/', [
		'uses' => 'ProjectController@denyRequest'
	]);

	Route::get('projects/{project}', [
		'as' => 'projects.show',
		'uses' => 'ProjectController@show'
	]);
	Route::post('projects', [
		'uses' => 'ProjectController@store'
	]);
	Route::post('update_project', [
		'uses' => 'ProjectController@update'
	]);
	Route::post('alter_admin', [
		'uses' => 'ProjectController@alterAdmin'
	]);
	Route::post('delete_project', [
		'uses' => 'ProjectController@destroy'
	]);

	Route::post('chattings', [
		'uses' => 'ChattingController@store'
	]);
	Route::post('chat_list', [
		'uses' => 'ChattingController@getNewList'
	]);

	Route::post('rolls', [
		'uses' => 'RollController@store'
	]);
	
	Route::post('update_roll', [
		'uses' => 'RollController@updateRoll'
	]);
});
