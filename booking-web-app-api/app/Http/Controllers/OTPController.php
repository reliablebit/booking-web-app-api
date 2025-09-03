<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class OTPController extends Controller
{
    /**
     * Send OTP to user's phone/email
     */
    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('phone', $request->phone)->first();

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $user->phone, $otp, 300);

        // In production, send SMS here
        // For development, return OTP in response
        return response()->json([
            'message' => 'OTP sent successfully',
            'otp' => $otp, // Remove this in production
            'expires_in' => 300
        ]);
    }

    /**
     * Verify OTP and login user
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('phone', $request->phone)->first();
        $cachedOTP = Cache::get('otp_' . $user->phone);

        if (!$cachedOTP || $cachedOTP != $request->otp) {
            return response()->json(['error' => 'Invalid or expired OTP'], 401);
        }

        // Clear OTP from cache
        Cache::forget('otp_' . $user->phone);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        // Load user with roles
        $user = User::with('roles')->find($user->id);

        return response()->json([
            'message' => 'OTP verified successfully',
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
}
