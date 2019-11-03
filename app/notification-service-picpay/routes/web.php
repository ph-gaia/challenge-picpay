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
    return "Welcome to the Picpay Test. =) <br> Microservice notification!";
});

Route::post('/email/welcome', 'MailController@welcome');
Route::post('/email/transaction', 'MailController@transaction');