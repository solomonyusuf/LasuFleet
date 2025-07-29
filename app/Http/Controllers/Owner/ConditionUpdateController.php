<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConditionUpdate;
use OpenApi\Annotations as OA;

class ConditionUpdateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/owners/{ownerId}/condition-updates",
     *     summary="Get condition updates for owner's vehicles",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="List of updates")
     * )
     */
    public function index($ownerId)
    {
        return ConditionUpdate::where('owner_id', $ownerId)->get();
    }

    /**
     * @OA\Post(
     *     path="/api/owners/{ownerId}/condition-updates",
     *     summary="Create a condition update",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vehicleId", "condition"},
     *             @OA\Property(property="vehicleId", type="integer", example=5),
     *             @OA\Property(property="condition", type="string", example="Good"),
     *             @OA\Property(property="notes", type="string", example="No visible issues")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request, $ownerId)
    {
        $request->validate([
            'vehicleId' => 'required|exists:vehicles,id',
            'condition' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $update = ConditionUpdate::create([
            'owner_id' => $ownerId,
            'vehicle_id' => $request->vehicleId,
            'condition' => $request->condition,
            'notes' => $request->notes,
        ]);

        return response()->json($update, 201);
    }
}
