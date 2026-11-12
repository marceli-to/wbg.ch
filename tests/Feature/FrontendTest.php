<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Client;
use App\Models\Content;
use App\Models\Grid;
use App\Models\GridLayout;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_static_pages_render(): void
    {
        foreach (['home', 'haltung', 'kompetenzen'] as $key) {
            Content::create(['key' => $key, 'text' => '<p>Text</p>', 'publish' => 1]);
        }

        foreach (['/', '/projekte', '/profil/haltung', '/profil/kompetenzen', '/profil/kunden', '/profil/rechtliches', '/agb', '/team', '/kontakt'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_unpublished_projects_are_not_public(): void
    {
        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);
        $draft = $this->project(['name' => 'Draft', 'category_id' => $category->id, 'publish' => 0, 'order' => 1, 'is_brands' => 0]);

        $this->get("/projekt/{$draft->id}/draft")->assertNotFound();
    }

    public function test_project_page_and_prev_next_navigation(): void
    {
        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);
        $a = $this->project(['name' => 'Alpha', 'category_id' => $category->id, 'publish' => 1, 'order' => 1, 'is_brands' => 0]);
        $b = $this->project(['name' => 'Beta', 'category_id' => $category->id, 'publish' => 1, 'order' => 2, 'is_brands' => 0]);
        $c = $this->project(['name' => 'Gamma', 'category_id' => $category->id, 'publish' => 1, 'order' => 3, 'is_brands' => 0]);

        $this->get("/projekt/{$b->id}/beta")
            ->assertOk()
            ->assertSee("/projekt/{$a->id}/alpha", false)
            ->assertSee("/projekt/{$c->id}/gamma", false);

        // Wraps around at the ends
        $this->get("/projekt/{$a->id}/alpha")->assertOk()->assertSee("/projekt/{$c->id}/gamma", false);
    }

    public function test_panoptikum_query_count_does_not_grow_with_projects(): void
    {
        $panoptikum = Category::forceCreate(['id' => Category::PANOPTIKUM_ID, 'name' => 'Panoptikum', 'publish' => 1, 'order' => 1]);
        $layout = GridLayout::create(['key' => '1fr']);

        $count = function () {
            DB::flushQueryLog();
            DB::enableQueryLog();
            $this->get('/projekte/'.Category::PANOPTIKUM_ID.'/panoptikum')->assertOk();

            return count(DB::getQueryLog());
        };

        $make = function (int $n) use ($panoptikum, $layout) {
            for ($i = 0; $i < $n; $i++) {
                $p = $this->project(['name' => 'P'.$i, 'category_id' => $panoptikum->id, 'subcategory_id' => 1, 'publish' => 1, 'order' => $i, 'is_brands' => 0]);
                Grid::create(['project_id' => $p->id, 'layout_id' => $layout->id, 'order' => 1, 'publish' => 1]);
            }
        };

        $make(2);
        $few = $count();
        $make(10);
        $many = $count();

        $this->assertSame($few, $many);
    }

    public function test_menu_cache_is_refreshed_when_a_project_changes(): void
    {
        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);
        $this->project(['name' => 'Erstes Projekt', 'category_id' => $category->id, 'publish' => 1, 'order' => 1, 'is_brands' => 0]);

        $this->get('/agb')->assertSee('Erstes Projekt');

        $this->project(['name' => 'Zweites Projekt', 'category_id' => $category->id, 'publish' => 1, 'order' => 2, 'is_brands' => 0]);

        $this->get('/agb')->assertSee('Zweites Projekt');
    }

    public function test_relations_to_unpublished_projects_are_hidden(): void
    {
        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);
        $a = $this->project(['name' => 'Alpha', 'category_id' => $category->id, 'publish' => 1, 'order' => 1, 'is_brands' => 0]);
        $draft = $this->project(['name' => 'Entwurf', 'category_id' => $category->id, 'publish' => 0, 'order' => 2, 'is_brands' => 0]);
        $a->relations()->create(['related_project_id' => $draft->id]);

        $this->get("/projekt/{$a->id}/alpha")->assertOk()->assertDontSee("/projekt/{$draft->id}/", false);
    }

    public function test_sitemap_and_llms_txt_list_published_projects_only(): void
    {
        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);
        $this->project(['name' => 'Öffentlich', 'category_id' => $category->id, 'publish' => 1, 'order' => 1, 'is_brands' => 0]);
        $this->project(['name' => 'Entwurf', 'category_id' => $category->id, 'publish' => 0, 'order' => 2, 'is_brands' => 0]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('/projekt/', false)
            ->assertSee('oeffentlich', false)
            ->assertDontSee('entwurf', false);

        $this->get('/llms.txt')->assertOk()->assertSee('Öffentlich')->assertDontSee('Entwurf');
    }

    public function test_project_page_has_canonical_url_with_correct_slug(): void
    {
        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);
        $project = $this->project(['name' => 'Haus Capitol', 'category_id' => $category->id, 'publish' => 1, 'order' => 1, 'is_brands' => 0]);

        $this->get("/projekt/{$project->id}/irgendwas")
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.config('app.url').'/projekt/'.$project->id.'/haus-capitol">', false);
    }

    /**
     * Create a project; category and client are mandatory in the schema.
     */
    private function project(array $attributes): Project
    {
        $attributes['category_id'] ??= Category::firstOrCreate(['name' => 'Default'], ['publish' => 1, 'order' => 99])->id;
        $attributes['client_id'] ??= Client::firstOrCreate(['name' => 'Default'], ['publish' => 1, 'project_id' => 0])->id;

        return Project::create($attributes);
    }
}
