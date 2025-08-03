<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Admin",
 *     description="Admin endpoints for managing users"
 * )
 *
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     summary="List all users",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=42),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="role", type="string", example="owner"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     summary="Create a new user",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "role", "username", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@gmail.com"),
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="role", type="string", enum={"owner","manager","auditor","admin"}, example="owner"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=55),
     *             @OA\Property(property="status", type="string", example="created")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'username' => 'required|string|unique:users',
            'role' => 'required|in:owner,manager,auditor,admin',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        return response()->json([
            'id' => $user->id,
            'status' => 'created',
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/users/{userId}",
     *     summary="Update a user's status",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="inactive")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User status updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=42),
     *             @OA\Property(property="status", type="string", example="inactive")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $userId)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($userId);
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'id' => $user->id,
            'status' => $user->status,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/users/{userId}",
     *     summary="Delete a user",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="deleted")
     *         )
     *     )
     * )
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return response()->json(['status' => 'deleted']);
    }
}
