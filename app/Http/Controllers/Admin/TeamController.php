<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index(): JsonResponse
    {
        return response()->json(Team::orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Team::create($this->validated($request)), 201);
    }

    public function show(Team $team): JsonResponse
    {
        return response()->json($team);
    }

    public function update(Request $request, Team $team): JsonResponse
    {
        $team->update($this->validated($request));

        return response()->json($team);
    }

    public function destroy(Team $team): Response
    {
        $this->media->delete($team->media);
        $team->delete();

        return response()->noContent();
    }

    public function togglePublish(Team $team): JsonResponse
    {
        return $this->toggleFlag($team);
    }

    public function clone(Team $team): JsonResponse
    {
        $copy = $team->replicate();
        $copy->name = $team->name.' (Kopie)';
        $copy->media = null;
        $copy->publish = 0;
        $copy->save();

        return response()->json($copy, 201);
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, Team::class);
    }

    /**
     * Remove an uploaded portrait (also before the member was saved).
     */
    public function destroyMedia(string $filename): Response
    {
        Team::where('media', $filename)->update(['media' => null]);
        $this->media->delete($filename);

        return response()->noContent();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'firstname' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:50'],
            'role' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50'],
            'media' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
