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
$router->group(['prefix' => 'api', 'middleware' => ['cors']], function () use ($router) {

    $router->post('login',  ['uses' => 'UsersController@login']);

    $router->post('transactions', ['uses' => 'TransactionsController@create']);

});

$router->group(['prefix' => 'api', 'middleware' => ['cors', 'auth']], function () use ($router) {
    $router->get('users',  ['uses' => 'UsersController@findByNameOrUsername'])->name('user');

    $router->get('users/{id}', ['uses' => 'UsersController@findById'])->name('user.find');

    $router->post('users', ['uses' => 'UsersController@create'])->name('user.create');

    $router->put('users', ['uses' => 'UsersController@update'])->name('user.update');

    $router->delete('users/{id}', ['uses' => 'UsersController@destroy'])->name('user.destroy');
});