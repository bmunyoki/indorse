<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserController extends Controller{
    // Get all users
    public function getAllUsers(Request $request){
        $users = User::where('id', '!=', $request->authenticatedUser->id)->get();

        return response()->json([
            'users' => $users
        ], 200);
    }

    // Get a single user
    public function getSingleUser(Request $request){
        $username = $request->segment(3);

        $user = User::where('username', $username)->get();
        if($user->count() == 1){
            return response()->json([
                'user' => $user
            ], 200);
        }else{
            return response()->json([
                'error' => 'User with username '. $username .' not found'
            ], 404);
        }
        
    }

    // Update user
    public function updateUser(Request $request){
        // Check if username is taken
        $usernameTaken = User::where('username', $request->input('username'))->where('id', '!=', $request->authenticatedUser->id)->get();
        if($usernameTaken->count() > 0){
            return response()->json([
                'error' => 'Username is taken'
            ], 409);
        }

        // Check if email is taken
        $emailTaken = User::where('email', $request->input('email'))->where('id', '!=', $request->authenticatedUser->id)->get();
        if($emailTaken->count() > 0){
            return response()->json([
                'error' => 'Email is taken'
            ], 409);
        }

        if(User::where('id', $request->authenticatedUser->id)->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'username' => $request->input('username')
        ])){
            return response()->json([
                'message' => 'User account updated',
                'user' => User::where('id', $request->authenticatedUser->id)->first()
            ], 201);
        }else{
            return response()->json([
                'error' => 'Error updating user account'
            ], 500);
        }
    }

    // Delete user account
    public function deleteUser(Request $request){
        $username = $request->segment(3);

        if($username != $request->authenticatedUser->username){
            return response()->json([
                'error' => 'Request not allowed. Users can only delete their own account'
            ], 403);
        }

        if(User::where('username', $username)->delete()){
            return response()->json([
                'message' => 'User account had been deleted'
            ], 200);
        }else{
            return response()->json([
                'error' => 'Error deleting user account'
            ], 500);
        }
    }

}
