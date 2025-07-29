<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication Endpoints"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login using plate number and password",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"plateNumber", "password"},
     *             @OA\Property(property="plateNumber", type="string", example="LASU-123"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="jwt-token"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=42),
     *                 @OA\Property(property="role", type="string", example="owner"),
     *                 @OA\Property(property="plateNumber", type="string", example="LASU-123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'plateNumber' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by plate number
        $user = User::where('plate_number', $request->plateNumber)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create JWT token
        $token = auth()->login($user);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'role' => $user->role,
                'plateNumber' => $user->plate_number
            ]
        ]);
    }
}
