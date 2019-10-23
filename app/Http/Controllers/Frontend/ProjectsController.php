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
    return view(
      $this->view_path . '.index',
      [
        'menu' => $this->menuService->boot(),
        'projects' => $projects->sortBy('category.order')
      ]
    );
  }

  /**
   * List all projects by category
   * 
   */
  public function category($id = NULL, $slug = NULL)
  {
    $category = $this->category->findOrFail($id);
    $projects = $this->project->published()
                              ->with('previewImages')
                              ->where('category_id', '=', $id)
                              ->orderBy('order')
                              ->get();

    return view(
      $this->view_path . '.category',
      [
        'menu' => $this->menuService->boot(NULL, $id),
        'projects' => $projects,
        'page_title' => $category->name
      ]
    );
  }

  /**
   * Show a resource
   * 
   * @param int $id
   * @param int $slug
   */
  public function project($id = NULL, $slug = NULL)
  {
    $project = $this->project->with('client')->with('category')->findOrFail($id);
    return view(
      $this->view_path . '.project',
      [
        'menu'        => $this->menuService->boot($id, $project->category_id),
        'project'     => $project,
        'browse'      => $this->getProjectNav($project->id),
        'grids'       => $this->getProjectGrid($id),
        'page_title'  => $project->category->name
      ]
    );
  }

  /**
   * Get the grid elements for a project
   * 
   * @param int $projectId
   */
  protected function getProjectGrid($projectId)
  {
    $grids = $this->grid->byProject($projectId)
                        ->with('layout')
                        ->with('elements.image')
                        ->orderBy('order')
                        ->get();

    $project_grids = [];
    foreach($grids as $g)
    {
      $project_grids[$g->id]['key'] = $g->layout->key;

      // Sort elements by position
      $sorted = $g->elements->sortBy('position');
      $project_grids[$g->id]['elements'] = $sorted->values()->all();
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
    // Build project nav
    $projects = $this->project->published()
                              ->with('category')
                              ->orderBy('order')
                              ->get();

    $sorted = $projects->sortBy('category.order');

    $project_keys = [];
    foreach($sorted as $project)
    {
      $project_keys[] = (int) $project->id;
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
