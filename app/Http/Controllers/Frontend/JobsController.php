<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use Illuminate\View\View;

/**
 * Currently not routed (see routes/web.php).
 */
class JobsController extends Controller
{
    public function __construct(private MenuService $menuService) {}

    public function index(): View
    {
        return view('web.jobs.index', [
            'menu' => $this->menuService->boot(),
            'pageTitle' => 'Jobs',
        ]);
    }
}
