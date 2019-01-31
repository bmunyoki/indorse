<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    } 

    public function register(Request $request){
        $this->validate($this->request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Check if email is in use - return error 409 for conflict
        $emailTaken = User::where('email', $this->request->input('email'))->first();
        if ($emailTaken) {
            return response()->json([
                'error' => 'Email address already in use'
            ], 409);
        }

        // Check if username is in use - return error 409 for conflict
        $usernameTaken = User::where('username', $this->request->input('username'))->first();
        if ($usernameTaken) {
            return response()->json([
                'error' => 'Username already in use'
            ], 409);
        }

        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'), ['rounds' => 12]);
        $user->save();

        if ($user->id >= 1) {
            return response()->json([
                'message' => 'User account created',
                'user' => $user,
                'token' => $this->generate_token($user)
            ], 201);
        }
    }

    public function login(Request $request){
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'error' => 'Invalid user'
            ], 400);
        }  

        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'Authentication success',
                'token' => $this->generate_token($user)
            ], 200);
        } 
    }

    protected function generate_token(User $user) {
        $payload = [
            'iss' => "Indorse by Benjamin", 
            'sub' => $user->email,
            'iat' => time(), 
            'exp' => (time() + 60*60*60)
        ];
        
        return JWT::encode($payload, env('JWT_KEY'));
    }
    
}
