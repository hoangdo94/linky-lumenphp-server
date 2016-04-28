<?php

$app->get('/', function () use ($app) {
    return $app->version();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function($api) {

      $api->get('users', 'UsersController@index');
      $api->get('users/{id}', 'UsersController@get');
      $api->post('users', 'UsersController@create');
      $api->post('users/{id}', 'UsersController@update');
      $api->delete('users/{id}', 'UsersController@delete');

      $api->post('auth', 'AuthController@authenticate');

      $api->get('posts', 'PostsController@index');
      $api->get('posts/{id}', 'PostsController@get');
      $api->post('posts', 'PostsController@create');
      $api->get('posts/user/{id}', 'PostsController@getPostFromUserId');
      $api->post('posts/{id}', 'PostsController@update');
      $api->delete('posts/{id}', 'PostsController@delete');

      $api->get('types', 'TypesController@index');
      $api->get('types/{id}', 'TypesController@get');
      $api->post('types', 'TypesController@create');
      $api->post('types/{id}', 'TypesController@update');
      $api->delete('types/{id}', 'TypesController@delete');

      $api->get('categories', 'CategoriesController@index');
      $api->get('categories/{id}', 'CategoriesController@get');
      $api->post('categories', 'CategoriesController@create');
      $api->post('categories/{id}', 'CategoriesController@update');
      $api->delete('categories/{id}', 'CategoriesController@delete');

      $api->post('files', '\App\Http\Controllers\FileEntriesController@upload');
      $api->get('files/{id}', '\App\Http\Controllers\FileEntriesController@get');
      $api->delete('files/{id}', '\App\Http\Controllers\FileEntriesController@delete');
});
