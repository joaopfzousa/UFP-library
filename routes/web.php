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
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->post('login', 'AuthController@login');

        $router->get('users', 'UsersController@index');
        $router->get('users/{number}', 'UsersController@show');
        $router->get('users/{number}/reservations', 'UsersController@showAllReservations');
        $router->get('users/{number}/active-reservations', 'UsersController@showActiveReservations');
        $router->group(['middleware' => 'jwt.auth'], function() use ($router) {
            $router->post('users', 'UsersController@create');
            $router->delete('users/{number}', 'UsersController@destroy');
        });

        $router->get('books', 'BooksController@index');
        $router->get('books/{isbn}', 'BooksController@show');
        $router->group(['middleware' => ['jwt.auth', 'user.admin']], function() use ($router) {
            $router->post('books', 'BooksController@create');
            $router->put('books/{isbn}', 'BooksController@update');
            $router->delete('books/{isbn}', 'BooksController@destroy');
        });

        $router->group(['middleware' => 'jwt.auth'], function() use ($router) {
            $router->post('reservations', 'BookRequestController@create');
            $router->put('reservations/{reservationId}', 'BookRequestController@update');
            $router->delete('reservations/{reservationId}', 'BookRequestController@destroy');
        });
    });
});
