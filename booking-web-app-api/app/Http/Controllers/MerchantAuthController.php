<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Annotations as OA;

class MerchantAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/merchant/register",
     *     summary="Register a new merchant",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="merchant@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="company_name", type="string", example="ABC Transport"),
     *             @OA\Property(property="license_number", type="string", example="LIC123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Merchant registered successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|unique:users',
                'password' => 'required|min:6',
                'company_name' => 'required',
                'license_number' => 'required'
            ]);

            // Debug: log validated data
            Log::info('Validated registration data:', $validated);

            // Create User with merchant role
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
            ]);

// Assign Spatie role
            $user->assignRole('merchant');


            // Debug: log user creation
            Log::info('User created:', ['id' => $user->id, 'email' => $user->email]);

            // Create Merchant profile
            $merchant = Merchant::create([
                'user_id' => $user->id,
                'business_name' => $validated['company_name'], // maps to table column
                'category' => $validated['category'] ?? 'bus', // default if not provided
                'address' => $validated['address'] ?? null,
                'status' => 'pending'
            ]);

            // Debug: log merchant creation
            Log::info('Merchant profile created:', ['id' => $merchant->id]);

            // Generate JWT token
            $token = auth('api')->login($user);

            // Return JSON response with JWT token
            return response()->json([
                'status' => 'success',
                'message' => 'Merchant registered successfully',
                'user_id' => $user->id,
                'merchant_id' => $merchant->id,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Other errors
            Log::error('Merchant registration failed:', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
