<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grid;
use App\Models\GridElement;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Slots (image or article) inside a project grid row.
 */
class GridElementController extends Controller
{
    public function index(Grid $grid): JsonResponse
    {
        return response()->json(
            GridElement::byGrid($grid->id)->with('image.project', 'news')->get()
        );
    }

    /**
     * Body: {"position": 0, "project_image_id": 5} or {"position": 0, "news_id": 2}
     */
    public function store(Request $request, Grid $grid): JsonResponse
    {
        $data = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
            'project_image_id' => ['required_without:news_id', 'nullable', 'integer', 'exists:project_images,id'],
            'news_id' => ['required_without:project_image_id', 'nullable', 'integer', 'exists:news,id'],
        ]);

        $element = DB::transaction(function () use ($grid, $data) {
            $element = GridElement::create([
                'grid_id' => $grid->id,
                'project_id' => isset($data['project_image_id']) ? $grid->project_id : null,
                'project_image_id' => $data['project_image_id'] ?? null,
                'news_id' => isset($data['project_image_id']) ? null : $data['news_id'],
                'position' => $data['position'],
            ]);

            if ($element->project_image_id) {
                ProjectImage::whereKey($element->project_image_id)->update(['is_grid' => 1]);
            }

            return $element;
        });

        return response()->json($element->load('image.project', 'news'), 201);
    }

    public function destroy(GridElement $element): Response
    {
        DB::transaction(function () use ($element) {
            if ($element->project_image_id) {
                ProjectImage::whereKey($element->project_image_id)->update(['is_grid' => 0]);
            }
            $element->delete();
        });

        return response()->noContent();
    }
}
