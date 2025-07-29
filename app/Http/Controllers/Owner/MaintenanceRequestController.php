<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use OpenApi\Annotations as OA;

class MaintenanceRequestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/owners/{ownerId}/maintenance-requests",
     *     summary="List maintenance requests for owner",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="List of maintenance requests")
     * )
     */
    public function index($ownerId)
    {
        return MaintenanceRequest::where('owner_id', $ownerId)->get();
    }

    /**
     * @OA\Post(
     *     path="/api/owners/{ownerId}/maintenance-requests",
     *     summary="Submit a maintenance request",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vehicleId", "issue", "priority"},
     *             @OA\Property(property="vehicleId", type="integer", example=5),
     *             @OA\Property(property="issue", type="string", example="Brake failure"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="high")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request, $ownerId)
    {
        $request->validate([
            'vehicleId' => 'required|exists:vehicles,id',
            'issue' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $requestData = MaintenanceRequest::create([
            'owner_id' => $ownerId,
            'vehicle_id' => $request->vehicleId,
            'issue' => $request->issue,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return response()->json($requestData, 201);
    }
}
