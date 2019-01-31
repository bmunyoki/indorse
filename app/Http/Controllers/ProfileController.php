<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;

use App\User;
use App\Model\Profile;

class ProfileController extends Controller{
    // Create user profile
    public function createProfile(Request $request){
    	$profile = new Profile();
    	$profile->user_id = $request->authenticatedUser->id;
    	$profile->about = $request->input('about');
    	$profile->school = $request->input('school');
    	$profile->phone = $request->input('phone');
    	$profile->status = $request->input('status');
    	$profile->save();

    	if($profile->id >= 1){
    		return response()->json([
                'message' => 'User profile created',
                'profile' => $profile
            ], 201);
    	}else{
    		return response()->json([
                'error' => 'Error creating user profile created'
            ], 500);
    	}
    }

    // Update user profile
    public function updateProfile(Request $request){
    	if(Profile::where('user_id', $request->authenticatedUser->id)->update([
    		'about' => $request->input('about'),
    		'school' => $request->input('school'),
    		'phone' => $request->input('phone'),
    		'status' => $request->input('status')
    	])){
    		return response()->json([
                'message' => 'User profile updated',
                'profile' => Profile::where('user_id', $request->authenticatedUser->id)->first()
            ], 201);
    	}else{
    		return response()->json([
                'error' => 'Error updating user profile.'
            ], 500);
    	}
    }

    // Get user profile - a user can only get their own profile
    public function getProfile(Request $request){
    	$username = $request->segment(3);

    	if($username != $request->authenticatedUser->username){
    		return response()->json([
                'error' => 'Request not allowed. Users can only get their own profile'
            ], 403);
    	}


    	return response()->json([
            'profile' => Profile::where('user_id', $request->authenticatedUser->id)->first()
        ], 200);
    }
}
