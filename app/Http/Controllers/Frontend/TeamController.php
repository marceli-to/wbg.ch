<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\MenuService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
  // Services
  protected $menuService;

  // Models
  protected $team;

  // View path
  protected $view_path = 'web.team';

  public function __construct(MenuService $menuService, Team $team)
  {
    $this->menuService = $menuService;
    $this->team        = $team;
  }

  public function index()
  {
    $team = $this->team->published()->orderBy('order')->get();
    return
      view($this->view_path . '.index')
      ->withTeam($team)
      ->withMenu($this->menuService->boot())
      ->withPageTitle('Team');
  }
}
