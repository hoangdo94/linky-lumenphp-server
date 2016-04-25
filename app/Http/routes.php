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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function($api) {

      $api->get('users',['middleware' => 'auth', 'uses' => 'UsersController@index']);
      $api->get('users/{id}','UsersController@get');
      $api->post('users','UsersController@create');
      $api->put('users/{id}','UsersController@update');
      $api->delete('users/{id}','UsersController@delete');

      $api->post('auth', ['middleware' => 'auth', 'uses' => 'AuthController@authenticate']);

      $api->get('post','PostController@index');

      $api->get('post/{id}','PostController@getPost');

      $api->post('post','PostController@createPost');

      $api->get('post/user/{:id}','PostController@getPostFromUserId');

      $api->put('post/{id}','PostController@updatePost');

      $api->delete('post/{id}','PostController@deletePost');

      $api->get('type','TypeController@index');

      $api->get('type/{id}','TypeController@getType');

      $api->post('type','TypeController@createType');

      $api->put('type/{id}','TypeController@updateType');

      $api->delete('type/{id}','TypeController@deleteType');

      $api->get('category','CategoryController@index');

      $api->get('category/{id}','CategoryController@getCategory');

      $api->post('category','CategoryController@createCategory');

      $api->put('category/{id}','CategoryController@updateCategory');

      $api->delete('category/{id}','CategoryController@deleteCategory');
});
