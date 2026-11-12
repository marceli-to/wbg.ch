<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeGrid;
use App\Models\HomeGridElement;
use App\Models\HomeGridLayout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Grid rows of the home page.
 *
 * Element changes are staged: new elements are created with
 * environment=development and deletions of live elements are marked with
 * action=delete. "deploy" publishes the staged changes, "reset" discards them.
 */
class HomeGridController extends Controller
{
    public function layouts(): JsonResponse
    {
        return response()->json(HomeGridLayout::all());
    }

    public function index(): JsonResponse
    {
        return response()->json($this->grids());
    }

    /**
     * Prepend a row. Body: {"layout_id": 3}. Returns all rows.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'layout_id' => ['required', 'integer', 'exists:home_grid_layouts,id'],
        ]);

        HomeGrid::create(['layout_id' => $data['layout_id'], 'order' => -1, 'publish' => 1]);

        return response()->json($this->grids(), 201);
    }

    public function destroy(HomeGrid $grid): Response
    {
        DB::transaction(function () use ($grid) {
            HomeGridElement::where('grid_id', $grid->id)->delete();
            $grid->delete();
        });

        return response()->noContent();
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, HomeGrid::class);
    }

    public function deploy(): Response
    {
        DB::transaction(function () {
            HomeGridElement::toDelete()->delete();
            HomeGridElement::isDevelopment()->update(['environment' => 'production']);
        });

        return response()->noContent();
    }

    public function reset(): Response
    {
        DB::transaction(function () {
            HomeGridElement::isDevelopment()->delete();
            HomeGridElement::toDelete()->update(['environment' => 'production', 'action' => 'keep']);
        });

        return response()->noContent();
    }

    private function grids()
    {
        return HomeGrid::with('layout', 'elements')->orderBy('order')->get();
    }
}
