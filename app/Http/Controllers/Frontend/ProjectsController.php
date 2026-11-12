<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Grid;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\MenuService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    protected string $view_path = 'web.projects';

    public function __construct(private MenuService $menuService) {}

    /**
     * List all projects
     */
    public function projects(): View
    {
        $projects = Project::published()
            ->with('previewImages')
            ->orderBy('order')
            ->get();

        return view($this->view_path.'.index', [
            'menu' => $this->menuService->boot(),
            'subCategories' => Category::SUBCATEGORIES,
            'projects' => $projects->shuffle()->all(),
            'pageTitle' => 'Projekte',
        ]);
    }

    /**
     * List all projects of a category
     */
    public function category(int $categoryId, ?string $slug = null): View
    {
        $category = Category::findOrFail($categoryId);
        $projects = Project::published()
            ->with('previewImages')
            ->where('category_id', $categoryId)
            ->orderBy('order')
            ->get();

        $data = [
            'menu' => $this->menuService->boot(null, $categoryId),
            'pageTitle' => $category->name,
            'metaDescription' => config('seo.descriptions.'.strtolower($category->name)),
            'isPanoptikum' => false,
            'projects' => $projects,
            'canonical' => AppHelper::canonical('projekte/'.$category->id.'/'.Str::slug($category->name)),
        ];

        if ($categoryId == Category::PANOPTIKUM_ID) {
            $grids = $this->getProjectGrids($projects->pluck('id'));

            $data = array_merge($data, [
                'projects' => $projects->shuffle(),
                'mobileProjects' => $projects->map(fn (Project $project) => [
                    'title' => $project->name,
                    'grid' => $grids[$project->id] ?? [],
                ])->all(),
                'pageTitle' => 'Panoptikum',
                'subCategories' => Category::SUBCATEGORIES,
                'isPanoptikum' => true,
            ]);
        }

        return view($this->view_path.'.category', $data);
    }

    /**
     * List all projects of a (Panoptikum) subcategory
     */
    public function subcategory(int $category, ?string $categorySlug, int $subcategory): View
    {
        $category = Category::findOrFail($category);
        abort_unless(isset(Category::SUBCATEGORIES[$subcategory]), 404);

        $projects = Project::published()
            ->where('category_id', $category->id)
            ->where('subcategory_id', $subcategory)
            ->orderBy('order')
            ->get();

        $grids = $this->getProjectGrids($projects->pluck('id'));

        return view($this->view_path.'.subcategory', [
            'menu' => $this->menuService->boot(null, $category->id, $subcategory),
            'projects' => $projects->map(fn (Project $project) => [
                'title' => $project->name,
                'grid' => $grids[$project->id] ?? [],
            ])->all(),
            'metaDescription' => config('seo.descriptions.'.strtolower($category->name).'_'.strtolower(Category::SUBCATEGORIES[$subcategory])),
            'pageTitle' => $category->name,
            'canonical' => AppHelper::canonical('projekte/'.$category->id.'/'.Str::slug($category->name).'/'.$subcategory.'/'.Str::slug(Category::SUBCATEGORIES[$subcategory])),
        ]);
    }

    /**
     * Show a project
     */
    public function project(int $id): View
    {
        $project = Project::published()
            ->with([
                'client',
                'category',
                // Only link related projects that are publicly visible
                'relations' => fn ($q) => $q->whereHas('related', fn ($q) => $q->published()),
                'relations.related.category',
                'relations.related.images',
                'relations.related.previewRelatedImages',
            ])
            ->findOrFail($id);

        // Open graph image (first published preview image)
        $ogImage = ProjectImage::where('project_id', $id)
            ->where('publish', 1)
            ->where('is_preview', 1)
            ->value('name');

        return view($this->view_path.'.project', [
            'menu' => $this->menuService->boot($id, $project->category_id),
            'project' => $project,
            'grids' => $this->getProjectGrids(collect([$id]), (bool) $project->is_brands)[$id] ?? [],
            'browse' => $this->getProjectNav($project->id),
            'pageTitle' => $project->category->name,
            'ogImage' => $ogImage,
            'canonical' => AppHelper::canonical('projekt/'.AppHelper::slug($project)),
        ]);
    }

    /**
     * Grid rows (with their elements) for several projects in one go.
     *
     * @return array<int, array> keyed by project id
     */
    protected function getProjectGrids(Collection $projectIds, bool $isBrands = false): array
    {
        $grids = Grid::whereIn('project_id', $projectIds)
            ->with(['layout', 'elements.news.competence', 'elements.image.client', 'elements.image.project'])
            ->orderBy('order')
            ->get()
            ->groupBy('project_id');

        $result = [];
        foreach ($grids as $projectId => $projectGrids) {
            $result[$projectId] = $isBrands
                ? $this->brandGrid($projectGrids)
                : $this->sortedGrid($projectGrids);
        }

        return $result;
    }

    /**
     * Regular grid: rows keyed by grid id, elements sorted by position.
     */
    private function sortedGrid(Collection $grids): array
    {
        $rows = [];
        foreach ($grids as $g) {
            $rows[$g->id] = [
                'key' => $g->layout->key,
                'elements' => $g->elements->sortBy('position')->values()->all(),
            ];
        }

        return $rows;
    }

    /**
     * Logo projects: all elements shuffled into rows of two.
     */
    private function brandGrid(Collection $grids): array
    {
        $rows = [];
        $chunks = $grids->flatMap->elements->shuffle()->chunk(2);
        foreach ($chunks as $key => $chunk) {
            $rows[$key] = [
                'key' => '2x1fr-logo',
                'elements' => $chunk->values()->all(),
            ];
        }

        return $rows;
    }

    /**
     * Previous/next project, in menu order (categories excl. Panoptikum,
     * then projects), wrapping around at both ends.
     *
     * @return array{prev: ?Project, next: ?Project}
     */
    protected function getProjectNav(int $projectId): array
    {
        $ids = Project::published()
            ->join('categories', 'categories.id', '=', 'projects.category_id')
            ->where('categories.publish', 1)
            ->where('categories.id', '!=', Category::PANOPTIKUM_ID)
            ->orderBy('categories.order')
            ->orderBy('projects.order')
            ->pluck('projects.id')
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($ids->isEmpty()) {
            return ['prev' => null, 'next' => null];
        }

        $last = $ids->count() - 1;
        $key = $ids->search($projectId);
        $key = $key === false ? 0 : $key;

        $prevId = $ids[$key === 0 ? $last : $key - 1];
        $nextId = $ids[$key === $last ? 0 : $key + 1];

        $projects = Project::whereKey([$prevId, $nextId])->get()->keyBy('id');

        return ['prev' => $projects[$prevId] ?? null, 'next' => $projects[$nextId] ?? null];
    }
}
