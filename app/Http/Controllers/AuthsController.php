<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthsController extends Controller
{
    // Register method
    public function register(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Check if the email is already registered
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json([
                'message' => 'Email is already registered. Please log in or use a different email address.',
                'success' => false
            ], 200); // HTTP 409 Conflict
        }

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']), // Encrypt the password
        ]);

        // Generate a token for the user
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Return a response with the token
        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'Bearer',
            ],
        ]);
    }

    // Login method
    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;
            return response()->json(['authorisation' => ['token' => $token]]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    // User details method
    public function userDetails(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        // Log out the user session (Sanctum uses cookie-based session)
        Auth::guard('web')->logout();  // Logs out the user for web-based guards (Sanctum)

        // This will invalidate the Sanctum token from the session
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
