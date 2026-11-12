<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetenceMedia;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CompetenceMediaController extends Controller
{
    public function __construct(private MediaService $media) {}

    /**
     * Remove an uploaded image (also before the competence was saved).
     */
    public function destroy(string $filename): Response
    {
        CompetenceMedia::where('name', $filename)->delete();
        $this->media->delete($filename);

        return response()->noContent();
    }

    public function togglePublish(CompetenceMedia $media): JsonResponse
    {
        return $this->toggleFlag($media);
    }
}
