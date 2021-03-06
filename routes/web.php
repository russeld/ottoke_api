<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// auth routes
$router->post('auth/login', ['uses' => 'AuthController@login']);
$router->post('auth/join', ['uses' => 'AuthController@join']);

$router->post('todos/swap', ['uses' => 'TodoController@swap']);

$router->group(['prefix' => '{uuid}/todos'], function() use ($router) {
    $router->get('/', ['uses' => 'TodoController@index']);
    $router->post('/', ['uses' => 'TodoController@store']);
    $router->get('/{todoId}', ['uses' => 'TodoController@show']);
    $router->delete('/{todoId}', ['uses' => 'TodoController@destroy']);
    $router->put('/{todoId}', ['uses' => 'TodoController@update']);
});


$router->group(['prefix' => '{uuid}/tags'], function() use ($router) {
    $router->get('/', ['uses' => 'TagsController@index']);
    $router->post('/', ['uses' => 'TagsController@store']);
    $router->post('/tagId', ['uses' => 'TagsController@delete']);
});

$router->group(['prefix' => 'clients'], function() use ($router) {
    $router->get('/', ['uses' => 'ClientController@index']);
    $router->post('/',['uses' => 'ClientController@store']);

    $router->group(['prefix' => '{uuid}/sheets'], function() use ($router) {
        $router->get('/', ['uses' => 'SheetController@index']);
        $router->get('/{sheetId}', ['uses' => 'SheetController@show']);
        $router->post('/', ['uses' => 'SheetController@store']);
        $router->put('/{sheetId}', ['uses' => 'SheetController@update']);
        $router->delete('/{sheetId}', ['uses' => 'SheetController@delete']);
    });
});

