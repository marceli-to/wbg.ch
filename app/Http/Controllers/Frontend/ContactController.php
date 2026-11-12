<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(private MenuService $menuService) {}

    public function index(): View
    {
        return view('web.contact.index', [
            'menu' => $this->menuService->boot(),
            'pageTitle' => 'Kontakt',
        ]);
    }
}
