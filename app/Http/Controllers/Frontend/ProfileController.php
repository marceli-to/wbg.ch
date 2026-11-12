<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Competence;
use App\Models\Content;
use App\Services\MenuService;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected string $view_path = 'web.profile';

    public function __construct(private MenuService $menuService) {}

    public function attitude(): View
    {
        return view($this->view_path.'.attitude', [
            'menu' => $this->menuService->boot(),
            'content' => Content::where('key', 'haltung')->first(),
            'pageTitle' => 'Haltung',
        ]);
    }

    public function competences(): View
    {
        return view($this->view_path.'.competences', [
            'menu' => $this->menuService->boot(),
            'competences' => Competence::published()->with('category', 'media')->orderBy('order')->get(),
            'content' => Content::where('key', 'kompetenzen')->first(),
            'category' => new Category,
            'pageTitle' => 'Kompetenzen',
        ]);
    }

    public function clients(): View
    {
        $clients = Client::published()
            ->with(['project' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get();

        $clientList = [];
        foreach ($clients as $c) {
            $key = is_numeric(substr($c->name, 0, 1)) ? '0–9' : substr($c->name, 0, 1);
            $clientList[$key][] = $c;
        }

        return view($this->view_path.'.clients', [
            'menu' => $this->menuService->boot(),
            'clients' => $clientList,
            'pageTitle' => 'Kunden',
        ]);
    }

    public function legal(): View
    {
        return view($this->view_path.'.legal', [
            'menu' => $this->menuService->boot(),
            'pageTitle' => 'Rechtliches',
        ]);
    }

    public function toc(): View
    {
        return view($this->view_path.'.toc', [
            'menu' => $this->menuService->boot(),
            'pageTitle' => 'AGB',
        ]);
    }
}
