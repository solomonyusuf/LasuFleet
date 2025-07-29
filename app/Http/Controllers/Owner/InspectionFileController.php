<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InspectionFile;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class InspectionFileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/owners/{ownerId}/inspection-files",
     *     summary="List inspection files for owner",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="List of uploaded files")
     * )
     */
    public function index($ownerId)
    {
        return InspectionFile::where('owner_id', $ownerId)->get(['id', 'filename', 'uploaded_at']);
    }

    /**
     * @OA\Post(
     *     path="/api/owners/{ownerId}/inspection-files",
     *     summary="Upload inspection file",
     *     tags={"Owner"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="ownerId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"vehicleId", "file"},
     *                 @OA\Property(property="vehicleId", type="integer", example=5),
     *                 @OA\Property(property="file", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="File uploaded")
     * )
     */
    public function store(Request $request, $ownerId)
    {
        $request->validate([
            'vehicleId' => 'required|exists:vehicles,id',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $path = $request->file('file')->store('inspections');

        $file = InspectionFile::create([
            'owner_id' => $ownerId,
            'vehicle_id' => $request->vehicleId,
            'filename' => $path,
        ]);

        return response()->json($file, 201);
    }
}
