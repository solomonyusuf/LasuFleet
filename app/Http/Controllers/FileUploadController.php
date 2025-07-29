<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use OpenApi\Annotations as OA;

class FileUploadController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/upload-file",
     *     summary="Upload a file to public/uploads directory",
     *     tags={"General"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(property="file", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="File uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="file_url", type="string", example="http://localhost/uploads/myfile.pdf")
     *         )
     *     )
     * )
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // 5MB limit
        ]);

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $uploadPath = public_path('uploads');

        // Create directory if not exists
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Move the file
        $file->move($uploadPath, $filename);

        $fileUrl = url("uploads/{$filename}");

        return response()->json([
            'file_url' => $fileUrl,
        ], 201);
    }
}
