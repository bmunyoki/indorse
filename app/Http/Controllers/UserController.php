<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserController extends Controller{
    // Get all users
    public function getAllUsers(){
        $users = User::with('profile')->get();
        return response()->json($users);
    }

    // Create user profile
    public function createUserProfile(Request $request){
        return response()->json($request->authenticatedUser);
    }
}
