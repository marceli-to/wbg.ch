<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Content::all());
    }

    /**
     * Available content keys: [{"key": "home", "page": "Startseite"}, …]
     */
    public function keys(): JsonResponse
    {
        return response()->json(config('content.content_keys'));
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Content::create($this->validated($request)), 201);
    }

    public function show(Content $content): JsonResponse
    {
        return response()->json($content);
    }

    public function update(Request $request, Content $content): JsonResponse
    {
        $content->update($this->validated($request));

        return response()->json($content);
    }

    public function destroy(Content $content): Response
    {
        $content->delete();

        return response()->noContent();
    }

    public function togglePublish(Content $content): JsonResponse
    {
        return $this->toggleFlag($content);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'key' => ['required', 'string', Rule::in(array_column(config('content.content_keys'), 'key'))],
            'text' => ['nullable', 'string'],
        ]);
    }
}
