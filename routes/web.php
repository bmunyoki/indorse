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

// Public routes - no authentication required
$router->get('/', function () use ($router) {
	$message = "Welcome to Friend finder API. use /api/auth/register with first_name, last_name, "; 
	$message .= "username, email and password to register";

	return response()->json([
        'message' => $message
    ], 200);
});
$router->post('api/auth/register', [ 'uses' => 'AuthController@register' ]);
$router->post('api/auth/login', [ 'uses' => 'AuthController@login' ]);


// Protected routes - a token is required to access the resource
$router->group(['middleware' => 'tokenMiddleware'], function() use ($router) {
	$router->post('api/profile/create', [ 'uses' => 'ProfileController@createProfile' ]);
	$router->post('api/profile/update', [ 'uses' => 'ProfileController@updateProfile' ]);
	$router->get('api/profile/{username}', [ 'uses' => 'ProfileController@getProfile' ]);

    $router->get('api/users', [ 'uses' => 'UserController@getAllUsers' ]);
});