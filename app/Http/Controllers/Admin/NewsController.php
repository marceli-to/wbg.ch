<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\HomeGridElement;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(News::all());
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(News::create($this->validated($request)), 201);
    }

    public function show(News $news): JsonResponse
    {
        return response()->json($news);
    }

    public function update(Request $request, News $news): JsonResponse
    {
        $news->update($this->validated($request));

        return response()->json($news);
    }

    public function destroy(News $news): JsonResponse|Response
    {
        if (HomeGridElement::where('news_id', $news->id)->exists()) {
            return response()->json([
                'message' => 'Der Artikel kann nicht gelöscht werden. (Grund: wird auf Homepage verwendet)',
            ], 422);
        }

        $news->delete();

        return response()->noContent();
    }

    public function togglePublish(News $news): JsonResponse
    {
        return $this->toggleFlag($news);
    }

    public function clone(News $news): JsonResponse
    {
        $copy = $news->replicate();
        $copy->title = $news->title.' (Kopie)';
        $copy->publish = 0;
        $copy->save();

        return response()->json($copy, 201);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'text' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:255'],
            'linkInternal' => ['nullable', 'integer', 'exists:competences,id'],
            'linkText' => ['nullable', 'string', 'max:255'],
            'linkNewWindow' => ['boolean'],
        ]);

        $data['link'] = AppHelper::addScheme($data['link'] ?? null) ?: null;
        $data['linkInternal'] = $data['linkInternal'] ?? null;
        $data['linkNewWindow'] = (int) ($data['linkNewWindow'] ?? 0);

        return $data;
    }
}
