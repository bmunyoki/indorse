<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class TokenMiddleware {
	public function handle($request, Closure $next, $guard = null){
        $token = $request->get('token');
        
        // Check if token is present
        if(!$token) {
            return response()->json([
                'error' => 'Token is required'
            ], 401);
        }

        // Decode the token
        try {
            $payload = JWT::decode($token, env('JWT_KEY'), ['HS256']);

        } catch(ExpiredException $e) {

            return response()->json([
                'error' => 'Expired token'
            ], 400);

        } catch(Exception $e) {

            return response()->json([
                'error' => 'Invalid token'
            ], 400);
        }

        // Find the user associated with the token
        $user = User::where('email', $payload->sub)->first();
        
        // Assign the user to a request variable
        $request->authenticatedUser = $user;

        return $next($request);
    }
}