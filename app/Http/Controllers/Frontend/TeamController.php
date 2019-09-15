<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\NavigationService;
use App\Services\MediaService;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // Services
    protected $mediaService;
    
    // Models
    protected $team;

    // View path
    protected $view_path = 'web.pages.team';

    public function __construct(
        MediaService $mediaService,
        Team $team
    )
    {
        $this->mediaServices = $mediaService;
        $this->team = $team;
    }

    public function index()
    {
        $team = $this->team->orderBy('order', 'ASC')->get();
        return view($this->view_path . '.index', ['team' => $team]);
    }
}