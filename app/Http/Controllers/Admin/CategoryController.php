<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Competence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::orderBy('order')->get());
    }

    /**
     * Subcategories are a fixed list: {"1": "Archiv", …}
     */
    public function subcategories(): JsonResponse
    {
        return response()->json(Category::SUBCATEGORIES);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Category::create($this->validated($request)), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $category->update($this->validated($request));

        return response()->json($category);
    }

    public function destroy(Category $category): Response
    {
        DB::transaction(function () use ($category) {
            Competence::where('category_id', $category->id)->update(['category_id' => null]);
            $category->delete();
        });

        return response()->noContent();
    }

    public function togglePublish(Category $category): JsonResponse
    {
        return $this->toggleFlag($category);
    }

    public function clone(Category $category): JsonResponse
    {
        $copy = $category->replicate();
        $copy->name = $category->name.' (Kopie)';
        $copy->publish = 0;
        $copy->save();

        return response()->json($copy, 201);
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, Category::class);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);
    }
}
