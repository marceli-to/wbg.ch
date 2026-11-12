<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\HomeGrid;
use App\Services\MenuService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private MenuService $menuService) {}

    public function index(): View
    {
        return view('web.home.index', [
            'menu' => $this->menuService->boot(),
            'grids' => $this->getGrids(),
            'intro' => Content::where('key', 'home')->first(),
            'pageTitle' => 'Home',
        ]);
    }

    /**
     * Home grid rows with their live (production) elements, sorted by position.
     */
    private function getGrids(): array
    {
        $grids = HomeGrid::with([
            'layout',
            'elements' => fn ($q) => $q->where('environment', 'production')->orderBy('position'),
            'elements.projectimage.project',
            'elements.news.competence',
        ])
            ->orderBy('order')
            ->get();

        $rows = [];
        foreach ($grids as $g) {
            $rows[$g->id] = [
                'key' => $g->layout->key,
                'elements' => $g->elements->all(),
            ];
        }

        return $rows;
    }
}
