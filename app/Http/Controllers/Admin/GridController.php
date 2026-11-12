<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grid;
use App\Models\GridElement;
use App\Models\GridLayout;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Grid rows of a project detail page.
 */
class GridController extends Controller
{
    public function layouts(): JsonResponse
    {
        return response()->json(GridLayout::all());
    }

    public function index(Project $project): JsonResponse
    {
        return response()->json($this->grids($project));
    }

    /**
     * Append a row. Body: {"layout_id": 3}. Returns all rows of the project.
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'layout_id' => ['required', 'integer', 'exists:grid_layouts,id'],
        ]);

        Grid::create([
            'project_id' => $project->id,
            'layout_id' => $data['layout_id'],
            'order' => 999,
            'publish' => 1,
        ]);

        return response()->json($this->grids($project), 201);
    }

    public function destroy(Grid $grid): Response
    {
        DB::transaction(function () use ($grid) {
            $imageIds = GridElement::where('grid_id', $grid->id)->pluck('project_image_id')->filter();
            ProjectImage::whereKey($imageIds)->update(['is_grid' => 0]);
            GridElement::where('grid_id', $grid->id)->delete();
            $grid->delete();
        });

        return response()->noContent();
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, Grid::class);
    }

    private function grids(Project $project)
    {
        return Grid::byProject($project->id)->with('layout')->orderBy('order')->get();
    }
}
