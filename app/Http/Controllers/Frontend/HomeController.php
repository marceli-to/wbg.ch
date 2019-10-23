<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Services\MediaService;
use App\Services\MenuService;

use App\Models\Project;
use App\Models\Content;
use App\Models\HomeGrid;
use App\Models\HomeGridElement;

use Illuminate\Http\Request;

class HomeController extends Controller
{

  // Services
  protected $menuService;
  protected $mediaService;

  // Models
  protected $project;
  protected $homeGrid;
  protected $content;

  // View path
  protected $view_path = 'web.home';

  // Key for content
  protected $content_key = 'home';

  public function __construct(
    MenuService $menuService,
    MediaService $mediaService,
    Project $project,
    HomeGrid $homeGrid,
    HomeGridElement $homeGridElement,
    Content $content
  )
  {
    $this->project          = $project;
    $this->homeGrid         = $homeGrid;
    $this->homeGridElement  = $homeGridElement;
    $this->menuService      = $menuService;
    $this->content          = $content;
  }

  public function index()
  {
    return view(
      $this->view_path . '.index', 
      [
        'menu'   => $this->menuService->boot(),
        'grids'  => $this->getGrids(),
        'intro'  => $this->content->where('key', '=', $this->content_key)->get()->first()
      ]
    );
  }

  /**
   * Return grids
   */

  private function getGrids()
  {
    $grids = $this->homeGrid->with('layout')
                            ->with('elements.projectimage.project')
                            ->with('elements.news')
                            ->orderBy('order')
                            ->get();
    
    $home_grids = [];
    foreach($grids as $g)
    {
        $home_grids[$g->id]['key'] = $g->layout->key;

        // Filter by environment & sort by position
        $sorted = $g->elements->where('environment', 'production')->sortBy('position');
        $home_grids[$g->id]['elements'] = $sorted->values()->all();
    }

    return $home_grids;
  }
}
