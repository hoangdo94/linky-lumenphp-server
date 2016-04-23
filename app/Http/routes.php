<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'api','namespace' => 'App\Http\Controllers'], function($app)
{
    $app->get('users','UsersController@index');
    $app->get('users/{id}','UsersController@get');
    $app->post('users','UsersController@create');
    $app->put('users/{id}','UsersController@update');
    $app->delete('users/{id}','UsersController@delete');
});
