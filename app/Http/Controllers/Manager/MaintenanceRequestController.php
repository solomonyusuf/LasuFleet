<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\AuditLog;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Manager", description="Manager Maintenance Approval")
 */
class MaintenanceRequestController extends Controller
{
    /**
     * @OA\Patch(
     *     path="/api/manager/maintenance-requests/{requestId}",
     *     summary="Approve or reject a maintenance request",
     *     tags={"Manager"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="requestId",
     *         in="path",
     *         required=true,
     *         description="Maintenance request ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"action"},
     *             @OA\Property(property="action", type="string", enum={"approve", "reject"}, example="reject")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=202),
     *             @OA\Property(property="status", type="string", example="rejected")
     *         )
     *     )
     * )
     */
    public function approve(Request $request, $requestId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $maintenance = MaintenanceRequest::findOrFail($requestId);
        $maintenance->status = $request->action;
        $maintenance->save();

        AuditLog::create([
            'type' => 'maintenance',
            'request_id' => $maintenance->id,
            'changed_by' => 'manager',
        ]);

        return response()->json([
            'id' => $maintenance->id,
            'status' => $maintenance->status
        ]);
    }
}
