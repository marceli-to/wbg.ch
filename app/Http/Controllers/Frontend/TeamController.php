<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\MenuService;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(private MenuService $menuService) {}

    public function index(): View
    {
        return view('web.team.index', [
            'team' => Team::published()->orderBy('order')->get(),
            'menu' => $this->menuService->boot(),
            'pageTitle' => 'Team',
        ]);
    }
}
