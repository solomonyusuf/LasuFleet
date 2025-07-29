<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FuelRequest;
use OpenApi\Annotations as OA;

class FuelRequestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/owners/{ownerId}/fuel-requests",
     *     summary="Get all fuel requests for owner",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="List fuel requests")
     * )
     */
    public function index($ownerId)
    {
        return FuelRequest::where('owner_id', $ownerId)->get();
    }

    /**
     * @OA\Post(
     *     path="/api/owners/{ownerId}/fuel-requests",
     *     summary="Submit a new fuel request",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vehicleId", "litres", "reason"},
     *             @OA\Property(property="vehicleId", type="integer", example=5),
     *             @OA\Property(property="litres", type="number", example=30),
     *             @OA\Property(property="reason", type="string", example="Weekly operation")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Fuel request created")
     * )
     */
    public function store(Request $request, $ownerId)
    {
        $request->validate([
            'vehicleId' => 'required|exists:vehicles,id',
            'litres' => 'required|numeric|min:1',
            'reason' => 'required|string',
        ]);

        $fuelRequest = FuelRequest::create([
            'owner_id' => $ownerId,
            'vehicle_id' => $request->vehicleId,
            'litres' => $request->litres,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json($fuelRequest, 201);
    }
}
