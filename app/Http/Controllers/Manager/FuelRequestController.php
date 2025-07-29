<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FuelRequest;
use App\Models\AuditLog;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Manager", description="Manager Fuel Approval")
 */
class FuelRequestController extends Controller
{
    /**
     * @OA\Patch(
     *     path="/api/manager/fuel-requests/{requestId}",
     *     summary="Approve or reject a fuel request",
     *     tags={"Manager"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="requestId",
     *         in="path",
     *         required=true,
     *         description="Fuel request ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"action"},
     *             @OA\Property(property="action", type="string", enum={"approve", "reject"}, example="approve")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=101),
     *             @OA\Property(property="status", type="string", example="approved")
     *         )
     *     )
     * )
     */
    public function approve(Request $request, $requestId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $fuelRequest = FuelRequest::findOrFail($requestId);
        $fuelRequest->status = $request->action;
        $fuelRequest->save();

        AuditLog::create([
            'type' => 'fuel',
            'request_id' => $fuelRequest->id,
            'changed_by' => 'manager',
        ]);

        return response()->json([
            'id' => $fuelRequest->id,
            'status' => $fuelRequest->status
        ]);
    }
}
