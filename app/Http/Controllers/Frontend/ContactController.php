<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Models\Job;
use Illuminate\Http\Request;

class ContactController extends Controller
{
  // Services
  protected $menuService;

  // View path
  protected $view_path = 'web.contact';

  // Model
  protected $job;

  public function __construct(MenuService $menuService, Job $job)
  {
    $this->menuService = $menuService;
    $this->job = $job;
  }

  public function index()
  {

    // $jobs = $this->job->published()->orderBy('order', 'ASC')->get();

    return 
      view($this->view_path . '.index')
      //->withJobs($jobs)
      ->withMenu($this->menuService->boot())
      ->withPageTitle('Kontakt');
  }
}
