<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid email or password.',
                ], 401);
            }

            // ✅ Create token with custom expiry
            $tokenResult = $user->createToken('admin-access-token');
            $token = $tokenResult->token;

            if ($request->remember) {
                $token->expires_at = now()->addWeeks(2);
            } else {
                $token->expires_at = now()->addHours(2);
            }

            $token->save();

            return response()->json([
                'token' => $tokenResult->accessToken,
                'expires_at' => $token->expires_at->toDateTimeString(),
                'url' => '/admin',
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage(), // You can hide this in production
            ], 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $status = Password::broker()->sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? response()->json([
                    'message' => 'Please check your email and reset your password.',
                    'status' => $status,
                ])
                : response()->json([
                    'message' => 'Failed to send reset link.',
                    'status' => $status,
                ], 422);
        } catch (\Exception $e) {
            Log::error('Forgot Password Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while sending reset link. Please try again later.',
            ], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $status = Password::broker()->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Password reset successfully.'])
                : response()->json(['message' => __($status)], 422);
        } catch (\Exception $e) {
            Log::error('Reset Password Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while resetting the password. Please try again later.',
            ], 500);
        }
    }
}
