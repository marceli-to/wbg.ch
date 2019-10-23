<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Services\MenuService;
use App\Services\MediaService;

use App\Models\Competence;
use App\Models\CompetenceMedia;
use App\Models\Client;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
  // Services
  protected $menuService;

  // Models
  protected $client;
  protected $competence;
  protected $competenceMedia;

  // View path
  protected $view_path = 'web.profile';

  /**
   * Constructor
   * 
   * @param MediaService $mediaService
   * @param Client $client
   * @param Competence $competence
   * @param CompetenceMedia $competenceMedia
   */

  public function __construct(
    MediaService $mediaService,
    Client $client,
    Competence $competence,
    CompetenceMedia $competenceMedia,
    MenuService $menuService
  )
  {
    $this->mediaService     = $mediaService;
    $this->client           = $client;
    $this->competence       = $competence;
    $this->competenceMedia  = $competenceMedia;
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

  public function attitude()
  {
    return view(
      $this->view_path . '.attitude',
      [
        'menu' => $this->menuService->boot(),
      ]
    );
  }

  public function competences()
  {
    return view(
      $this->view_path . '.competences',
      [
        'menu' => $this->menuService->boot(),
      ]
    );
  }

  public function clients()
  {
    return view(
      $this->view_path . '.clients',
      [
        'menu' => $this->menuService->boot(),
      ]
    );
  }

  public function imprint()
  {
    return view(
      $this->view_path . '.imprint',
      [
        'menu' => $this->menuService->boot(),
      ]
    );
  }
}
