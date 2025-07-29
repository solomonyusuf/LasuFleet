<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Carbon\Carbon;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Auditor",
 *     description="Auditor log endpoints"
 * )
 *
 */
class AuditLogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/auditor/logs",
     *     summary="Get audit logs with optional filters",
     *     tags={"Auditor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of log",
     *         required=false,
     *         @OA\Schema(type="string", enum={"fuel", "maintenance", "condition"})
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Start date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="End date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of audit logs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="logId", type="integer", example=9001),
     *                 @OA\Property(property="type", type="string", example="fuel"),
     *                 @OA\Property(property="requestId", type="integer", example=101),
     *                 @OA\Property(property="changedBy", type="string", example="manager"),
     *                 @OA\Property(property="timestamp", type="string", format="date-time", example="2025-07-20T10:00:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from')) {
            $query->whereDate('timestamp', '>=', Carbon::parse($request->from));
        }

        if ($request->filled('to')) {
            $query->whereDate('timestamp', '<=', Carbon::parse($request->to));
        }

        return response()->json($query->get()->map(function ($log) {
            return [
                'logId' => $log->id,
                'type' => $log->type,
                'requestId' => $log->request_id,
                'changedBy' => $log->changed_by,
                'timestamp' => $log->timestamp,
            ];
        }));
    }
}
