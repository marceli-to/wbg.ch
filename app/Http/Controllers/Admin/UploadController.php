<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct(private MediaService $media) {}

    /**
     * Upload an image. Returns {"name": "…", "filetype": "jpg"}.
     */
    public function image(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:20480'],
        ]);

        return response()->json($this->media->upload($request->file('file')), 201);
    }

    /**
     * Upload a pdf document. Returns {"name": "…", "filetype": "pdf"}.
     */
    public function document(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        return response()->json($this->media->uploadDocument($request->file('file')), 201);
    }
}
