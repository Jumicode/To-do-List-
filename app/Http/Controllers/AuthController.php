<?php

namespace App\Http\Controllers;


/**
 * Class AuthController
 *
 * Handles user authentication and registration using JWT.
 *
 * Methods:
 * - register(Request $request): Registers a new user and returns a JWT token.
 * - login(Request $request): Authenticates a user and returns a JWT token.
 * - me(): Returns the authenticated user's information.
 * - logout(): Logs out the authenticated user and invalidates the token.
 *
 * @package App\Http\Controllers
 */

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Handles user registration
    public function register(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create new user with hashed password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate JWT token for the new user
        $token = JWTAuth::fromUser($user);

        // Return user data and token
        return response()->json(compact('user', 'token'), 201);
    }

    // Handles user login
    public function login(Request $request)
    {
        // Get only email and password from request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate and generate token
        if (!$token = JWTAuth::attempt($credentials)) {
            // If authentication fails, return error
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Return JWT token
        return response()->json(compact('token'));
    }

    // Returns the authenticated user's information
    public function me()
    {
        return response()->json(auth()->user());
    }

    // Logs out the authenticated user
    public function logout()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Invalidate the user's token
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);

    }
}

