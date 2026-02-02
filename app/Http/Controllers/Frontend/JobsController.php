<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Services\MenuService;

class JobsController extends Controller
{
  protected $menuService;
  protected $view_path = 'web.jobs';

  public function __construct(MenuService $menuService)
  {
    $this->menuService = $menuService;
  }

  public function index()
  {
    return
      view($this->view_path . '.index')
      ->withMenu($this->menuService->boot())
      ->withPageTitle('Jobs');
  }
}
