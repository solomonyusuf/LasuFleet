<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Admin",
 *     description="Admin endpoints for managing vehicles"
 * )
 *
 */
class VehicleController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/vehicles",
     *     summary="Create a new vehicle",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "plateNumber", "registrationDate", "model", "color", "condition", "ownerId"
     *             },
     *             @OA\Property(property="plateNumber", type="string", example="LASU-123"),
     *             @OA\Property(property="registrationDate", type="string", format="date", example="2025-07-29"),
     *             @OA\Property(property="model", type="string", example="Toyota Camry"),
     *             @OA\Property(property="color", type="string", example="Black"),
     *             @OA\Property(property="condition", type="string", example="Good"),
     *             @OA\Property(property="ownerId", type="integer", example=42)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehicle created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=5),
     *             @OA\Property(property="status", type="string", example="created")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'plateNumber' => 'required|string|unique:vehicles,plate_number',
            'registrationDate' => 'required|date',
            'model' => 'required|string',
            'color' => 'required|string',
            'condition' => 'required|string',
            'ownerId' => 'required|exists:users,id',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => $request->plateNumber,
            'registration_date' => $request->registrationDate,
            'model' => $request->model,
            'color' => $request->color,
            'condition' => $request->condition,
            'owner_id' => $request->ownerId,
        ]);

        return response()->json([
            'id' => $vehicle->id,
            'status' => 'created'
        ], 201);
    }
}
