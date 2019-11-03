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

$router->get('/', function () use ($router) {
    return "Welcome to the Picpay Test. =) <br> :: API Gateway ::";
});

$router->group(['prefix' => 'api', 'middleware' => ['cors']], function () use ($router) {

    $router->post('login',  ['uses' => 'UsersController@login']);

    $router->post('users', ['uses' => 'UsersController@create']);
});

$router->group(['prefix' => 'api', 'middleware' => ['cors', 'auth']], function () use ($router) {

    $router->post('transactions', ['uses' => 'TransactionsController@create']);

    $router->get('users/{id}', ['uses' => 'UsersController@findById']);

    $router->get('users',  ['uses' => 'UsersController@findByNameOrUsername']);

    $router->put('users/{id}', ['uses' => 'UsersController@update']);

    $router->delete('users/{id}', ['uses' => 'UsersController@destroy']);
});
