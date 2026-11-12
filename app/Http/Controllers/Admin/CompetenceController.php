<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CompetenceController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index(): JsonResponse
    {
        return response()->json(Competence::with('category')->orderBy('order')->get());
    }

    /**
     * Body: competence fields + "media": [{"id"?, "name", "caption", "publish"}]
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $competence = DB::transaction(function () use ($data) {
            $competence = Competence::create($data);
            $this->syncMedia($competence, $data['media'] ?? []);

            return $competence;
        });

        return response()->json($competence->load('category', 'media'), 201);
    }

    public function show(Competence $competence): JsonResponse
    {
        return response()->json($competence->load('category', 'media'));
    }

    public function update(Request $request, Competence $competence): JsonResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($competence, $data) {
            $competence->update($data);
            $this->syncMedia($competence, $data['media'] ?? []);
        });

        return response()->json($competence->load('category', 'media'));
    }

    public function destroy(Competence $competence): Response
    {
        foreach ($competence->media as $media) {
            $this->media->delete($media->name);
            $media->delete();
        }
        $competence->delete();

        return response()->noContent();
    }

    public function togglePublish(Competence $competence): JsonResponse
    {
        return $this->toggleFlag($competence);
    }

    public function clone(Competence $competence): JsonResponse
    {
        $copy = $competence->replicate();
        $copy->title = $competence->title.' (Kopie)';
        $copy->publish = 0;
        $copy->save();

        return response()->json($copy, 201);
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, Competence::class);
    }

    private function syncMedia(Competence $competence, array $items): void
    {
        foreach ($items as $item) {
            $competence->media()->updateOrCreate(
                ['id' => $item['id'] ?? null],
                [
                    'name' => $item['name'],
                    'caption' => $item['caption'] ?? null,
                    'publish' => (int) ($item['publish'] ?? 0),
                ],
            );
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer'],
            'media' => ['array'],
            'media.*.id' => ['nullable', 'integer'],
            'media.*.name' => ['required', 'string', 'max:255'],
            'media.*.caption' => ['nullable', 'string', 'max:255'],
            'media.*.publish' => ['boolean'],
        ]);

        $data['category_id'] ??= null;
        // A subcategory only makes sense together with a category
        $data['subcategory_id'] = $data['category_id'] ? ($data['subcategory_id'] ?? null) : null;

        return $data;
    }
}
