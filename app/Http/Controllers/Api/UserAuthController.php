<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\ResponseHelper;
use App\Notifications\UserPasswordResetNotification;

/**
 * @OA\Tag(
 *     name="User Authentication",
 *     description="Endpoints for user registration, login, logout, and password reset"
 * )
 */
class UserAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Register a new user",
     *     tags={"User Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return ResponseHelper::withSuccess('User registered successfully.', $user);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Login user",
     *     tags={"User Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ResponseHelper::withError('Invalid credentials.', [], 401);
        }

        $token = $user->createToken('User-API-Token')->plainTextToken;

        return ResponseHelper::withSuccess('Login successful!', [
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/forgot-password",
     *     summary="Send password reset link",
     *     tags={"User Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link sent"
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $token = Str::random(64);

        $user->update([
            'reset_token' => $token,
            'reset_expires_at' => now()->addHour(),
        ]);

        $user->notify(new UserPasswordResetNotification($token));

        return ResponseHelper::withSuccess('A password reset link has been sent to your email.');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/reset-password",
     *     summary="Reset user password",
     *     tags={"User Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "reset_token", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="reset_token", type="string"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successful"
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->where('reset_token', $request->reset_token)
            ->first();

        if (!$user || ($user->reset_expires_at && now()->gt($user->reset_expires_at))) {
            return ResponseHelper::withError('Invalid or expired reset token.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_expires_at' => null,
        ]);

        return ResponseHelper::withSuccess('Password reset successful. You can now log in.');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Logout the authenticated user",
     *     tags={"User Authentication"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ResponseHelper::withSuccess('Logout successful.');
    }
}
