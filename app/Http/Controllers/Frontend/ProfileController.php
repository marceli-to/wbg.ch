<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Services\MenuService;
use App\Services\MediaService;

use App\Models\Competence;
use App\Models\CompetenceMedia;
use App\Models\Client;
use App\Models\Content;
use App\Models\Category;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
  // Services
  protected $menuService;

  // Models
  protected $client;
  protected $competence;
  protected $competenceMedia;
  protected $content;
  protected $category;

  // View path
  protected $view_path = 'web.profile';

  /**
   * Constructor
   * 
   * @param MediaService $mediaService
   * @param Client $client
   * @param Competence $competence
   * @param CompetenceMedia $competenceMedia
   * @param Content $content
   * @param Category $category
   */

  public function __construct(
    MediaService $mediaService,
    Client $client,
    Competence $competence,
    CompetenceMedia $competenceMedia,
    MenuService $menuService,
    Content $content,
    Category $category
  )
  {
    $this->mediaService     = $mediaService;
    $this->client           = $client;
    $this->competence       = $competence;
    $this->content          = $content;
    $this->competenceMedia  = $competenceMedia;
    $this->menuService      = $menuService;
    $this->category         = $category;
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
    return
      view($this->view_path . '.attitude')
      ->withMenu($this->menuService->boot())
      ->withContent($this->content->where('key', '=', 'haltung')->get()->first())
      ->withPageTitle('Haltung');
  }

  public function competences()
  {
    $competences = $this->competence->published()
                                    ->with('category')
                                    ->with('media')
                                    ->orderBy('order', 'ASC')
                                    ->get();

    return
      view($this->view_path . '.competences')
      ->withMenu($this->menuService->boot())
      ->withCompetences($competences)
      ->withContent($this->content->where('key', '=', 'kompetenzen')->get()->first())
      ->withCategory($this->category)
      ->withPageTitle('Kompetenzen');
  }

  public function clients()
  {
    $clients = $this->client->published()->with('project')->orderBy('name')->get();
    $client_list = [];

    if ($clients)
    {
      foreach($clients as $c)
      {
        $key = (is_numeric(substr($c->name, 0,1))) ? '0–9' : substr($c->name, 0,1);
        $client_list[$key][] = $c;
      }
    }
    
    return
      view($this->view_path . '.clients')
      ->withMenu($this->menuService->boot())
      ->withClients($client_list)
      ->withPageTitle('Kunden');
  }

  public function imprint()
  {
    return
      view($this->view_path . '.imprint')
      ->withMenu($this->menuService->boot())
      ->withPageTitle('Impressum');
  }
}
