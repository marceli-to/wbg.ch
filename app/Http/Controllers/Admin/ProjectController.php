<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProjectRelationsDeleted;
use App\Models\Grid;
use App\Models\GridElement;
use App\Models\HomeGridElement;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ProjectRelation;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    public function __construct(private MediaService $media) {}

    /**
     * All projects (with category, client and images), sorted by "order".
     * With ?published=1: only published projects, sorted by name (used by
     * the grid image pickers).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::with('category', 'client', 'images');

        if ($request->boolean('published')) {
            $query->published()->orderBy('name');
        } else {
            $query->orderBy('order');
        }

        return response()->json($query->get());
    }

    /**
     * Body: project fields + "images": [{"id"?, "name", "caption", "publish",
     * "url", "is_preview", "is_preview_related", "client_id"}]
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $project = DB::transaction(function () use ($data) {
            $project = Project::create($data);
            $this->syncImages($project, $data['images'] ?? []);

            return $project;
        });

        return response()->json($this->load($project), 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($this->load($project));
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($project, $data) {
            $project->update($data);
            $this->syncImages($project, $data['images'] ?? []);
        });

        return response()->json($this->load($project));
    }

    public function destroy(Request $request, Project $project): Response
    {
        $relations = ProjectRelation::with('project.category', 'related.category')
            ->where('project_id', $project->id)
            ->orWhere('related_project_id', $project->id)
            ->get();

        $images = $project->images;

        DB::transaction(function () use ($project, $relations, $images) {
            $gridIds = Grid::where('project_id', $project->id)->pluck('id');
            GridElement::whereIn('grid_id', $gridIds)->delete();
            Grid::whereKey($gridIds)->delete();

            HomeGridElement::whereIn('project_image_id', $images->pluck('id'))->delete();
            ProjectImage::where('project_id', $project->id)->delete();

            ProjectRelation::whereKey($relations->pluck('id'))->delete();
            $project->delete();
        });

        foreach ($images as $image) {
            $this->media->delete($image->name);
        }

        // Let the editor know which relations disappeared with the project
        if ($relations->isNotEmpty()) {
            $summary = $relations->map(fn (ProjectRelation $r) => [
                'from' => $r->project?->name.' ('.$r->project?->category?->name.')',
                'to' => $r->related?->name.' ('.$r->related?->category?->name.')',
            ])->all();

            Mail::to($request->user()->email)->send(new ProjectRelationsDeleted($summary));
        }

        return response()->noContent();
    }

    public function togglePublish(Project $project): JsonResponse
    {
        return $this->toggleFlag($project);
    }

    /**
     * Duplicate a project including relations, images (files are copied)
     * and grids. Grid elements are re-pointed to the copied images.
     */
    public function clone(Project $project): JsonResponse
    {
        $copy = DB::transaction(function () use ($project) {
            $copy = $project->replicate();
            $copy->name = $project->name.' (Kopie)';
            $copy->publish = 0;
            $copy->is_brands = 0;
            $copy->save();

            foreach ($project->relations as $relation) {
                $copy->relations()->save($relation->replicate());
            }

            $imageMap = [];
            foreach ($project->images as $image) {
                $imageCopy = $image->replicate();
                $imageCopy->name = $this->media->clone($image->name);
                $imageCopy->project_id = $copy->id;
                $imageCopy->save();
                $imageMap[$image->id] = $imageCopy->id;
            }

            // Keep crop parents pointing at the copies as well
            foreach ($imageMap as $newId) {
                $imageCopy = ProjectImage::find($newId);
                if ($imageCopy->parent_id && isset($imageMap[$imageCopy->parent_id])) {
                    $imageCopy->update(['parent_id' => $imageMap[$imageCopy->parent_id]]);
                }
            }

            foreach (Grid::with('elements')->where('project_id', $project->id)->get() as $grid) {
                $gridCopy = $grid->replicate();
                $gridCopy->project_id = $copy->id;
                $gridCopy->save();

                foreach ($grid->elements as $element) {
                    $elementCopy = $element->replicate();
                    $elementCopy->grid_id = $gridCopy->id;
                    $elementCopy->project_id = $element->project_id ? $copy->id : null;
                    $elementCopy->project_image_id = $imageMap[$element->project_image_id] ?? $element->project_image_id;
                    $elementCopy->save();
                }
            }

            return $copy;
        });

        return response()->json($copy, 201);
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, Project::class);
    }

    private function load(Project $project): Project
    {
        return $project->load('category', 'client', 'originalImages', 'relations.related');
    }

    private function syncImages(Project $project, array $images): void
    {
        foreach ($images as $image) {
            ProjectImage::updateOrCreate(
                ['id' => $image['id'] ?? null],
                [
                    'project_id' => $project->id,
                    'name' => $image['name'],
                    'caption' => $image['caption'] ?? null,
                    'publish' => (int) ($image['publish'] ?? 0),
                    'url' => $image['url'] ?? null,
                    'is_preview' => (int) ($image['is_preview'] ?? 0),
                    'is_preview_related' => (int) ($image['is_preview_related'] ?? 0),
                    'client_id' => $image['client_id'] ?? null,
                ],
            );
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'principal' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'video' => ['nullable', 'string', 'max:255'],
            'video_link' => ['nullable', 'string', 'max:255'],
            // Both are NOT NULL in the database
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'is_brands' => ['boolean'],
            'images' => ['array'],
            'images.*.id' => ['nullable', 'integer'],
            'images.*.name' => ['required', 'string', 'max:255'],
            'images.*.caption' => ['nullable', 'string', 'max:255'],
            'images.*.publish' => ['boolean'],
            'images.*.url' => ['nullable', 'string', 'max:255'],
            'images.*.is_preview' => ['boolean'],
            'images.*.is_preview_related' => ['boolean'],
            'images.*.client_id' => ['nullable', 'integer', 'exists:clients,id'],
        ]);

        foreach (['video', 'video_link', 'subcategory_id'] as $key) {
            $data[$key] = ($data[$key] ?? null) ?: null;
        }
        $data['is_brands'] = (int) ($data['is_brands'] ?? 0);

        return $data;
    }
}
