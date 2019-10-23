<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Services\MenuService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
  // Services
  protected $menuService;

  // View path
  protected $view_path = 'web.team';

  public function __construct(MenuService $menuService)
  {
    $this->menuService = $menuService;
  }

  public function index()
  {
    return view(
      $this->view_path . '.index',
      [
        'menu' => $this->menuService->boot(),
      ]
    );
  }
}
