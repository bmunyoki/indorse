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
// Entry point
$router->get('/', function () use ($router) {
	$message = "Welcome to Friend finder API. use /api/auth/register with first_name, last_name, "; 
	$message .= "username, email and password to register";

	return response()->json([
        'message' => $message
    ], 200);
});
// Register
$router->post('api/auth/register', [ 'uses' => 'AuthController@register' ]);

// Login
$router->post('api/auth/login', [ 'uses' => 'AuthController@login' ]);


// Protected routes - a token is required to access the resource
$router->group(['middleware' => 'tokenMiddleware'], function() use ($router) {
	// Create profile
	$router->post('api/profile/create', [ 'uses' => 'ProfileController@createProfile' ]);

	// Update profile
	$router->post('api/profile/update', [ 'uses' => 'ProfileController@updateProfile' ]);

	// Get own profile
	$router->get('api/profile/{username}', [ 'uses' => 'ProfileController@getProfile' ]);


	// Get all users
    $router->get('api/users', [ 'uses' => 'UserController@getAllUsers' ]);

    // Update user account
    $router->post('api/users/update', [ 'uses' => 'UserController@updateUser' ]);

    // Get a single user
    $router->get('api/users/{username}', [ 'uses' => 'UserController@getSingleUser' ]);

    // Delete user account
    $router->post('api/users/{username}/delete', [ 'uses' => 'UserController@deleteUser' ]);


    // Add user as a friend
    $router->post('api/friends/befriend/{username}', [ 'uses' => 'FriendController@befriendUser' ]);

    //Delete user from friend list
    $router->post('api/friends/unfriend/{username}', [ 'uses' => 'FriendController@unfriendUser' ]);
});