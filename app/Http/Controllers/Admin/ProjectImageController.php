<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GridElement;
use App\Models\HomeGridElement;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProjectImageController extends Controller
{
    public function __construct(private MediaService $media) {}

    /**
     * Published images of a project (incl. crops), originals first.
     */
    public function index(Project $project): JsonResponse
    {
        return response()->json(
            $project->images()->where('publish', 1)->orderBy('parent_id')->get()
        );
    }

    /**
     * Delete an image by filename. If it belongs to a crop family (an
     * original with its crops), the whole family is removed.
     */
    public function destroy(string $filename): Response
    {
        $image = ProjectImage::where('name', $filename)->first();

        if (! $image) {
            // Uploaded but never saved
            $this->media->delete($filename);

            return response()->noContent();
        }

        $images = $image->parent_id > 0
            ? ProjectImage::withCrop($image->parent_id)->get()
            : collect([$image]);

        $this->deleteImages($images);

        return response()->noContent();
    }

    /**
     * Delete a single cropped image by filename.
     */
    public function destroyCropped(string $filename): Response
    {
        $image = ProjectImage::where('name', $filename)->first();

        if ($image) {
            $this->deleteImages(collect([$image]));
        } else {
            $this->media->delete($filename);
        }

        return response()->noContent();
    }

    /**
     * Toggle publish | is_preview | is_preview_related.
     */
    public function toggle(ProjectImage $image, string $flag): JsonResponse
    {
        return $this->toggleFlag($image, $flag);
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, ProjectImage::class);
    }

    /**
     * Crop an image for a grid slot. The crop is stored as a new image of
     * the same project and the grid element is pointed to it.
     *
     * Body: {image, imageId, gridElementId, isHomeGrid, coords: {w, h, x, y}}
     */
    public function crop(Request $request): JsonResponse
    {
        $data = $request->validate([
            'image' => ['required', 'string', 'regex:/^[A-Za-z0-9._-]+$/'],
            'imageId' => ['required', 'integer', 'exists:project_images,id'],
            'gridElementId' => ['required', 'integer'],
            'isHomeGrid' => ['required', 'boolean'],
            'coords' => ['required', 'array'],
            'coords.w' => ['required', 'numeric', 'min:1'],
            'coords.h' => ['required', 'numeric', 'min:1'],
            'coords.x' => ['required', 'numeric', 'min:0'],
            'coords.y' => ['required', 'numeric', 'min:0'],
        ]);

        $element = $data['isHomeGrid']
            ? HomeGridElement::findOrFail($data['gridElementId'])
            : GridElement::findOrFail($data['gridElementId']);

        $cropped = $this->media->crop($data);

        DB::transaction(function () use ($data, $cropped, $element) {
            $original = ProjectImage::findOrFail($data['imageId']);
            $original->is_grid = 0;
            $original->parent_id = $original->parent_id ?: $original->id;
            $original->save();

            $copy = $original->replicate();
            $copy->name = $cropped['name'];
            $copy->is_crop = 1;
            $copy->is_preview = 0;
            $copy->is_preview_related = 0;
            $copy->is_grid = 1;
            $copy->save();

            $element->project_image_id = $copy->id;
            $element->save();
        });

        return response()->json($cropped);
    }

    /**
     * @param  Collection<int, ProjectImage>  $images
     */
    private function deleteImages($images): void
    {
        $ids = $images->pluck('id');

        DB::transaction(function () use ($ids) {
            GridElement::whereIn('project_image_id', $ids)->delete();
            HomeGridElement::whereIn('project_image_id', $ids)->delete();
            ProjectImage::whereKey($ids)->delete();
        });

        foreach ($images as $image) {
            $this->media->delete($image->name);
        }
    }
}
