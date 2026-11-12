<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeGrid;
use App\Models\HomeGridElement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Slots (image or article) inside a home page grid row. See HomeGridController
 * for the staging (development/production) workflow.
 */
class HomeGridElementController extends Controller
{
    /**
     * Elements of a row that are not marked for deletion.
     */
    public function index(HomeGrid $grid): JsonResponse
    {
        return response()->json(
            HomeGridElement::with('projectimage.project', 'news')
                ->where('grid_id', $grid->id)
                ->where('action', 'keep')
                ->get()
        );
    }

    /**
     * Body: {"position": 0, "project_image_id": 5} or {"position": 0, "news_id": 2}
     */
    public function store(Request $request, HomeGrid $grid): JsonResponse
    {
        $data = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
            'project_image_id' => ['required_without:news_id', 'nullable', 'integer', 'exists:project_images,id'],
            'news_id' => ['required_without:project_image_id', 'nullable', 'integer', 'exists:news,id'],
        ]);

        $element = HomeGridElement::create([
            'grid_id' => $grid->id,
            'project_image_id' => $data['project_image_id'] ?? null,
            'news_id' => isset($data['project_image_id']) ? null : $data['news_id'],
            'position' => $data['position'],
            'environment' => 'development',
            'action' => 'keep',
        ]);

        return response()->json($element->load('projectimage.project', 'news'), 201);
    }

    /**
     * Live elements are only marked for deletion (until deploy); staged
     * elements are removed right away.
     */
    public function destroy(HomeGridElement $element): Response
    {
        if ($element->environment === 'production') {
            $element->update(['action' => 'delete']);
        } else {
            $element->delete();
        }

        return response()->noContent();
    }
}
