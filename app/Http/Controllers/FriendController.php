<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Model\Friend;

class FriendController extends Controller{
	// Add a user as a friend
	public function befriendUser(Request $request){
		//Check if user exists
		$username = $request->segment(4);
		$user = User::where('username', $username)->get();
		
		if($user->count() != 1){
			return response()->json([
                'error' => 'User with username '. $username .' not found'
            ], 404);
		}

		// Check if user is befriending themself!
		if($username == $request->authenticatedUser->username){
			return response()->json([
                'error' => 'User not allowed to befriend themself!'
            ], 403);
		}

		// Check if user is already a friend
		$friends = Friend::where('friend_id', $user->first()->id)->get();
		if($friends->count() > 0){
			return response()->json([
                'error' => 'User is already your friend'
            ], 403);
		}

		// Add user to friends
		$friend = new Friend();
		$friend->user_id = $request->authenticatedUser->id;
		$friend->friend_id = $user->first()->id;
		$friend->save();

		if($friend->id >= 1){
			return response()->json([
	            'message' => 'User added to friend list'
	        ], 201);
		}else{
			return response()->json([
	            'error' => 'Error adding user added to friend list'
	        ], 500);
		}	
	}

	// Unfriend user.
	public function unfriendUser(Request $request){
		//Check if user exists
		$username = $request->segment(4);
		$user = User::where('username', $username)->get();
		
		if($user->count() != 1){
			return response()->json([
                'error' => 'User with username '. $username .' not found'
            ], 404);
		}

		// Check if user is unfriending themself!
		if($username == $request->authenticatedUser->username){
			return response()->json([
                'error' => 'User not allowed to unfriend themself!'
            ], 403);
		}

		// Check if user is not on friend list
		$friends = Friend::where('friend_id', $user->first()->id)->where('user_id', $request->authenticatedUser->id)->get();

		if($friends->count() < 1){
			return response()->json([
                'error' => 'User is not your friend!'
            ], 403);
		}

		// Unfriend user
		if(Friend::where('user_id', $request->authenticatedUser->id)->where('friend_id', $user->first()->id)->delete()){

			return response()->json([
	            'message' => 'User removed from your friend list'
	        ], 201);

		}else{
			return response()->json([
	            'error' => 'Error befriending user'
	        ], 500);
		}	
	}
}