<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Manager", description="Manager Vehicle Management")
 */
class VehicleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/manager/vehicles",
     *     summary="List all vehicles",
     *     tags={"Manager"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle list",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="plateNumber", type="string", example="LASU-123"),
     *                 @OA\Property(property="ownerId", type="integer", example=42),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="model", type="string", example="Toyota Camry")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Vehicle::select(['id', 'plate_number', 'owner_id', 'status', 'model'])
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'plateNumber' => $v->plate_number,
                    'ownerId' => $v->owner_id,
                    'status' => $v->status,
                    'model' => $v->model,
                ];
            });
    }

    /**
     * @OA\Put(
     *     path="/api/manager/vehicles/{vehicleId}",
     *     summary="Update a vehicle's status",
     *     tags={"Manager"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="vehicleId",
     *         in="path",
     *         required=true,
     *         description="Vehicle ID",
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
     *         description="Updated vehicle status",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=5),
     *             @OA\Property(property="status", type="string", example="inactive")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $vehicleId)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $vehicle = Vehicle::findOrFail($vehicleId);
        $vehicle->status = $request->status;
        $vehicle->save();

        return response()->json([
            'id' => $vehicle->id,
            'status' => $vehicle->status,
        ]);
    }
}
