<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use App\Services\MenuService;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Grid;
use App\Models\Category;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
  // Services
  protected $mediaService;
  protected $menuService;

  // Models
  protected $project;
  protected $projectImage;
  protected $grid;
  protected $category;

  // View path
  protected $view_path = 'web.projects';

  public function __construct(
    MediaService $mediaService,
    MenuService $menuService,
    Project $project,
    ProjectImage $projectImage,
    Grid $grid,
    Category $category
  )
  {
    $this->project      = $project;
    $this->projectImage = $projectImage;
    $this->grid         = $grid;
    $this->category     = $category;
    $this->menuService  = $menuService;
  }

  /**
   * List all projects
   * 
   */
  public function projects()
  {
    $projects = $this->project->published()
                              ->with('previewImages')
                              ->orderBy('order')
                              ->get();

    return
      view($this->view_path . '.index')
      ->withMenu($this->menuService->boot())
      ->withSubCategories($this->category->subcategories)
      ->withProjects($projects->shuffle()->all())
      ->withPageTitle('Projekte');
  }

  /**
   * List all projects by category
   * 
   */
  public function category($categoryId = NULL, $slug = NULL)
  {
    $category = $this->category->findOrFail($categoryId);
    $projects = $this->project->published()
                              ->with('previewImages')
                              ->where('category_id', '=', $categoryId)
                              ->orderBy('order')
                              ->get();

    if ($categoryId == $this->category->panoptikumId)
    {
      $data = [];
      foreach($projects as $project)
      {
        $data[] = [
          'title' => $project->name,
          'grid'  => $this->getProjectGrid($project->id)
        ];
      }
      return
        view($this->view_path . '.category')
        ->withMenu($this->menuService->boot(NULL, $categoryId))
        ->withProjects($projects->shuffle())
        ->withMobileProjects($data)
        ->withPageTitle('Panoptikum')
        ->withSubCategories($this->category->subcategories)
        ->withMetaDescription(config('seo.descriptions.' . strtolower($category->name)))
        ->withIsPanoptikum(TRUE);
    }

    return 
      view($this->view_path . '.category')
      ->withMenu($this->menuService->boot(NULL, $categoryId))
      ->withProjects($projects)
      ->withPageTitle($category->name)
      ->withMetaDescription(config('seo.descriptions.' . strtolower($category->name)))
      ->withIsPanoptikum(FALSE);
  }

  /**
   * List all projects by subcategory
   * 
   */
  public function subcategory(
    $category = NULL,
    $categorySlug = NULL,
    $subcategory = NULL,
    $subcategorySlug = NULL
  )
  {
    $category = $this->category->findOrFail($category);
    $projects = $this->project->published()
                              ->with('previewImages')
                              ->where('category_id', '=', $category->id)
                              ->where('subcategory_id', '=', $subcategory)
                              ->orderBy('order')
                              ->get();

    $data = [];
    foreach($projects as $project)
    {
      $data[] = [
        'title' => $project->name, // $this->category->subcategories[$subcategory],
        'grid'  => $this->getProjectGrid($project->id)
      ];
    }

    return
      view($this->view_path . '.subcategory')
      ->withMenu($this->menuService->boot(NULL, $category->id, $subcategory))
      ->withProjects($data)
      ->withMetaDescription(config('seo.descriptions.' . strtolower($category->name) . '_' . strtolower($this->category->subcategories[$subcategory])))
      ->withPageTitle($category->name);
  }

  /**
   * Show a resource
   * 
   * @param int $id
   * @param int $slug
   */
  public function project($id = NULL, $slug = NULL)
  {
    $project = $this->project->with('client')
                             ->with('category')
                             ->with('relations.related.images')
                             ->findOrFail($id);

    // Open graph image (first active image)
    $og_image = $this->projectImage->where('project_id', '=', $id)
                                   ->where('publish', '=', 1)
                                   ->where('is_preview', '=', 1)
                                   ->get()
                                   ->first();

    return
      view($this->view_path . '.project')
      ->withMenu($this->menuService->boot($id, $project->category_id))
      ->withProject($project)
      ->withGrids($this->getProjectGrid($id, $project->is_brands))
      ->withBrowse($this->getProjectNav($project->id))
      ->withPageTitle($project->category->name)
      ->withOgImage($og_image ? $og_image->name : null);
  }

  /**
   * Get the grid elements for a project
   * 
   * @param int $projectId
   */
  protected function getProjectGrid($projectId, $isBrands = FALSE)
  {
    $grids = $this->grid->byProject($projectId)
                        ->with('layout')
                        ->with('elements.news.competence')
                        ->with('elements.image.client')
                        ->orderBy('order')
                        ->get();

    // Projects containing logos will be display completely random
    if ($isBrands)
    {
      $elements = [];
      foreach($grids as $g)
      {
        foreach($g->elements as $element)
        {
          $elements[] = $element;
        }
      }
     
      $elements = collect($elements)->shuffle()->chunk(2);
      $project_grids = [];
      foreach($elements as $key => $element)
      {
        $project_grids[$key]['key'] = '2x1fr-logo';
        if ($element->count() == 1)
        {
          $project_grids[$key]['elements'][0] = $element->first();
        }
        else
        {
          $project_grids[$key]['elements'][0] = $element->first();
          $project_grids[$key]['elements'][1] = $element->last();
        }
      }
    }
    else
    {
      $project_grids = [];
      foreach($grids as $g)
      {
        $project_grids[$g->id]['key'] = $g->layout->key;
  
        // Sort elements by position or shuffle them
        $sorted = $g->elements->sortBy('position');
        $project_grids[$g->id]['elements'] = $sorted->values()->all();
      }
    }

    return $project_grids;
  }

  /**
   * Get the prev/next element
   * 
   * @param int $projectId
   */

  protected function getProjectNav($projectId = NULL)
  {
    $categories = $this->category->published()
                                 ->where('id', '!=', $this->category->panoptikumId)
                                 ->orderBy('order')
                                 ->get();

    foreach($categories as $category)
    {
      $projects[] = $this->project->published()
                                  ->where('category_id', '=', $category->id)
                                  ->orderBy('order')
                                  ->get();
    }

    $project_keys = [];
    foreach($projects as $project)
    {
      foreach($project as $p)
      {
        $project_keys[] = (int) $p->id;
      }
    }

    // Get current key
    $key = array_search($projectId, $project_keys);

    if ($key == 0)
    {
      $prevId = end($project_keys);
      $nextId = $project_keys[$key+1];
    }
    else if ($key == count($project_keys) - 1)
    {
      $prevId = $project_keys[$key-1];
      $nextId = $project_keys[0];
    }
    else
    {
      $prevId = $project_keys[$key-1];
      $nextId = $project_keys[$key+1];
    }

    return [
      'prev' => $this->project->find($prevId),
      'next' => $this->project->find($nextId)
    ];
  }
}
