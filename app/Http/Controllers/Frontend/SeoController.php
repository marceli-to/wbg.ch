<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use App\Models\Project;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Machine readable entry points: sitemap.xml and llms.txt.
 * All URLs use the canonical host (APP_URL), see AppHelper::canonical().
 */
class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $xml = view('web.seo.sitemap', ['urls' => $this->urls()])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function llms(): Response
    {
        $categories = $this->categories();
        $projects = $this->projects()->groupBy('category_id');

        $intro = Content::where('key', 'haltung')->value('text');
        $intro = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string) $intro), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        $out = [];
        $out[] = '# WBG AG – Visuelle Kommunikation';
        $out[] = '';
        $out[] = '> Designbüro in Zürich für Signaletik, Orientierungs- und Leitsysteme, Logo- und Markenentwicklung, Editorial-Design, Buchgestaltung, Kommunikationsmittel und Ausstellungsgestaltung.';
        $out[] = '';
        if ($intro) {
            $out[] = Str::limit($intro, 800);
            $out[] = '';
        }
        $out[] = 'Kontakt: WBG AG, Binzstrasse 39, CH-8045 Zürich, +41 44 269 43 43, mail@wbg.ch';
        $out[] = '';
        $out[] = '## Seiten';
        $out[] = '';
        $out[] = '- [Projekte]('.AppHelper::canonical('projekte').'): Übersicht aller Projekte';
        $out[] = '- [Haltung]('.AppHelper::canonical('profil/haltung').'): Gestalterische Haltung';
        $out[] = '- [Kompetenzen]('.AppHelper::canonical('profil/kompetenzen').'): Leistungen und Kompetenzen';
        $out[] = '- [Kunden]('.AppHelper::canonical('profil/kunden').'): Kundenliste';
        $out[] = '- [Team]('.AppHelper::canonical('team').'): Mitarbeitende';
        $out[] = '- [Kontakt]('.AppHelper::canonical('kontakt').'): Adresse und Kontakt';

        foreach ($categories as $category) {
            $items = $projects[$category->id] ?? collect();
            if ($items->isEmpty()) {
                continue;
            }
            $out[] = '';
            $out[] = '## Projekte: '.$category->name;
            $out[] = '';
            foreach ($items as $project) {
                $description = trim(strip_tags((string) $project->meta_description));
                $out[] = '- ['.$project->name.']('.$this->projectUrl($project).')'.($description ? ': '.Str::limit($description, 160) : '');
            }
        }

        return response(implode("\n", $out)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * @return array<int, array{loc: string, lastmod: ?string}>
     */
    private function urls(): array
    {
        $urls = [];
        $add = function (string $path, $lastmod = null) use (&$urls) {
            $urls[] = ['loc' => AppHelper::canonical($path), 'lastmod' => $lastmod?->toAtomString()];
        };

        $add('/');
        $add('projekte');

        $projects = $this->projects();

        foreach ($this->categories() as $category) {
            if (! $projects->contains('category_id', $category->id)) {
                continue;
            }
            $add('projekte/'.$category->id.'/'.Str::slug($category->name));

            if ($category->id == Category::PANOPTIKUM_ID) {
                $subcategories = $projects->where('category_id', $category->id)->pluck('subcategory_id')->filter()->unique()->sort();
                foreach ($subcategories as $sub) {
                    $add('projekte/'.$category->id.'/'.Str::slug($category->name).'/'.$sub.'/'.Str::slug(Category::SUBCATEGORIES[$sub] ?? ''));
                }
            }
        }

        foreach ($projects as $project) {
            $add('projekt/'.AppHelper::slug($project), $project->updated_at);
        }

        foreach (['profil/haltung', 'profil/kompetenzen', 'profil/kunden', 'team', 'kontakt', 'profil/rechtliches', 'agb'] as $path) {
            $add($path);
        }

        return $urls;
    }

    private function categories(): Collection
    {
        return Category::published()->orderBy('order')->get(['id', 'name']);
    }

    private function projects(): Collection
    {
        return Project::published()
            ->orderBy('order')
            ->get(['id', 'name', 'category_id', 'subcategory_id', 'meta_description', 'updated_at']);
    }

    private function projectUrl(Project $project): string
    {
        return AppHelper::canonical('projekt/'.AppHelper::slug($project));
    }
}
