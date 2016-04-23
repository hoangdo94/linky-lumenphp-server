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
    $app->get('post','PostController@index');
  
    $app->get('post/{id}','PostController@getPost');
      
    $app->post('post','PostController@createPost');

    $app->get('post','PostController@getPostFromUserId');
      
    $app->put('post/{id}','PostController@updatePost');
      
    $app->delete('post/{id}','PostController@deletePost');

    $app->get('type','TypeController@index');
  
    $app->get('type/{id}','TypeController@getType');
      
    $app->post('type','TypeController@createType');
      
    $app->put('type/{id}','TypeController@updateType');
      
    $app->delete('type/{id}','TypeController@deleteType');

    $app->get('category','CategoryController@index');
  
    $app->get('category/{id}','CategoryController@getCategory');
      
    $app->post('category','CategoryController@createCategory');
      
    $app->put('category/{id}','CategoryController@updateCategory');
      
    $app->delete('category/{id}','CategoryController@deleteCategory');
});
