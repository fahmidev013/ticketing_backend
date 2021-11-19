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

$router->post("register", "AuthController@register");
$router->post("login", "AuthController@login");
$router->get('coba/{id}', 'ExampleController@coba');
$router->get('category', 'ApiController@getCategory');
$router->post('notif', 'ApiController@getNotif');
$router->get('issue', 'ApiController@getIssue');
$router->get('priority', 'ApiController@getPriority');
$router->get('project', 'ApiController@getProject');
$router->get('resolution', 'ApiController@getResolution');
$router->get('status', 'ApiController@getStatus');
$router->get('type', 'ApiController@getType');
$router->get('upload', 'ApiController@upload');



// $router->get("user", "UserController@index");

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get("user", "UserController@index");
});

