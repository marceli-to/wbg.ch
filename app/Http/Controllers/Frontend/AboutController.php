<?php
namespace App\Http\Controllers\Frontend;

use App\Models\Competence;
use App\Models\CompetenceMedia;
use App\Models\Client;
use App\Services\MediaService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
  protected $mediaService;
  protected $client;
  protected $competence;
  protected $competenceMedia;

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
    CompetenceMedia $competenceMedia
  )
  {
    $this->mediaService     = $mediaService;
    $this->client           = $client;
    $this->competence       = $competence;
    $this->competenceMedia  = $competenceMedia;
  }

  public function attitude()
  {
    return view('web.about.attitude');
  }

  public function expertise()
  {
    return view('web.about.expertise');
  }

  public function clients()
  {
    return view('web.about.clients');
  }

  public function imprint()
  {
    return view('web.about.imprint');
  }
}
