<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:user,merchant,admin'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        // Assign role using Spatie
        $user->assignRole($request->role);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ]);
    }

    // User Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Get authenticated user
        $authenticatedUser = auth('api')->user();

        // Load user with roles from database
        $user = User::with('roles')->find($authenticatedUser->id);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => optional($user->roles->first())->name ?? 'user'
            ]
        ]);
    }

    // Current User
    public function me()
    {
        $authenticatedUser = auth('api')->user();

        // Load user with roles from database
        $user = User::with('roles')->find($authenticatedUser->id);

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => optional($user->roles->first())->name ?? 'user' // default to 'user' if no role
            ]
        ]);
    }



    // Logout
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
