<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Grid;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
  protected $mediaService;
  protected $project;
  protected $projectImage;
  protected $grid;

  protected $view_path = 'web.projects';

  protected $menu;

  public function __construct(
    MediaService $mediaService,
    Project $project,
    ProjectImage $projectImage,
    Grid $grid
  )
  {
    $this->project      = $project;
    $this->projectImage = $projectImage;
    $this->grid         = $grid;
  }

  /**
   * List all projects
   * 
   */
  public function projects()
  {
    return view($this->view_path . '.index');
  }

  /**
   * Show a resource
   * 
   * @param int $id
   * @param int $slug
   */
  public function project($id = NULL, $slug = NULL)
  {
    $project = $this->project->with('client')->findOrFail($id);
    return view(
        $this->view_path . '.project',
        [
          'project' => $project,
          'grids'   => $this->getProjectGrid($id)
        ]
    );
  }

  /**
   * Show a preview
   * 
   * @param Project $project
   */
  public function preview(Project $project)
  {
    return view(
      $this->view_path . '.preview',
      [
        'project'       => $project,
        'grids'         => $this->getProjectGrid($project->id),
        'is_preview'    => TRUE
      ]);
  }

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
}
