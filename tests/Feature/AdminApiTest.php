<?php

namespace Tests\Feature;

use App\Mail\ProjectRelationsDeleted;
use App\Models\Category;
use App\Models\Client;
use App\Models\Grid;
use App\Models\GridElement;
use App\Models\GridLayout;
use App\Models\HomeGrid;
use App\Models\HomeGridElement;
use App\Models\HomeGridLayout;
use App\Models\News;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Team;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_team_crud_toggle_clone_and_order(): void
    {
        $payload = ['firstname' => 'Anna', 'name' => 'Muster', 'email' => 'anna@example.com', 'role' => 'Design'];

        $id = $this->postJson('/api/team', $payload)->assertCreated()->json('id');

        $this->getJson('/api/team')->assertOk()->assertJsonCount(1);
        $this->putJson("/api/team/{$id}", [...$payload, 'role' => 'Lead'])->assertOk()->assertJsonPath('role', 'Lead');
        $this->patchJson("/api/team/{$id}/publish")->assertOk()->assertJsonPath('value', 1);
        $this->patchJson("/api/team/{$id}/publish")->assertOk()->assertJsonPath('value', 0);

        $copyId = $this->postJson("/api/team/{$id}/clone")->assertCreated()->assertJsonPath('name', 'Muster (Kopie)')->json('id');

        $this->putJson('/api/team/order', ['items' => [['id' => $id, 'order' => 2], ['id' => $copyId, 'order' => 1]]])->assertNoContent();
        $this->assertSame([$copyId, $id], Team::orderBy('order')->pluck('id')->all());

        $this->deleteJson("/api/team/{$id}")->assertNoContent();
        $this->assertModelMissing(Team::find($id) ?? new Team(['id' => $id]));
    }

    public function test_validation_errors_are_returned(): void
    {
        $this->postJson('/api/team', ['email' => 'not-an-email'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['firstname', 'name', 'email']);

        $this->postJson('/api/projects', ['name' => 'X', 'category_id' => 999])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('category_id');
    }

    public function test_project_store_with_images_and_show(): void
    {
        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);

        $client = Client::create(['name' => 'Kunde', 'publish' => 1, 'project_id' => 0]);

        $id = $this->postJson('/api/projects', [
            'name' => 'Plakat',
            'category_id' => $category->id,
            'client_id' => $client->id,
            'is_brands' => false,
            'images' => [
                ['name' => 'abc.jpg', 'caption' => 'Eins', 'publish' => true, 'is_preview' => true, 'is_preview_related' => false],
            ],
        ])->assertCreated()->json('id');

        $this->getJson("/api/projects/{$id}")
            ->assertOk()
            ->assertJsonPath('category.name', 'Print')
            ->assertJsonPath('original_images.0.caption', 'Eins');

        $this->getJson('/api/projects?published=1')->assertOk()->assertJsonCount(0);
        $this->patchJson("/api/projects/{$id}/publish")->assertJsonPath('value', 1);
        $this->getJson('/api/projects?published=1')->assertOk()->assertJsonCount(1);
    }

    public function test_project_clone_repoints_grid_elements_to_copied_images(): void
    {
        // Cloning copies the image file, so a real source file is needed
        $name = uniqid().'_clone-test.jpg';
        $path = storage_path('app/public/media/images/source/'.$name);
        imagejpeg(imagecreatetruecolor(10, 10), $path);

        $project = $this->project(['name' => 'Original', 'publish' => 1, 'order' => 1, 'is_brands' => 0]);
        $image = ProjectImage::create(['project_id' => $project->id, 'name' => $name, 'publish' => 1]);
        $layout = GridLayout::create(['key' => '1fr']);
        $grid = Grid::create(['project_id' => $project->id, 'layout_id' => $layout->id, 'order' => 1, 'publish' => 1]);
        GridElement::create(['grid_id' => $grid->id, 'project_id' => $project->id, 'project_image_id' => $image->id, 'position' => 0]);

        $copyId = $this->postJson("/api/projects/{$project->id}/clone")
            ->assertCreated()
            ->assertJsonPath('name', 'Original (Kopie)')
            ->assertJsonPath('publish', 0)
            ->json('id');

        $copiedImage = ProjectImage::where('project_id', $copyId)->firstOrFail();
        $copiedElement = GridElement::whereIn('grid_id', Grid::where('project_id', $copyId)->pluck('id'))->firstOrFail();

        $this->assertSame($copiedImage->id, (int) $copiedElement->project_image_id);
        $this->assertNotSame($name, $copiedImage->name);
        $this->assertFileExists(storage_path('app/public/media/images/source/'.$copiedImage->name));

        app(MediaService::class)->delete($name);
        app(MediaService::class)->delete($copiedImage->name);
    }

    public function test_project_destroy_removes_grids_images_and_relations_and_mails_editor(): void
    {
        Mail::fake();

        $category = Category::create(['name' => 'Print', 'publish' => 1, 'order' => 1]);
        $project = $this->project(['name' => 'A', 'category_id' => $category->id, 'publish' => 1, 'order' => 1, 'is_brands' => 0]);
        $other = $this->project(['name' => 'B', 'category_id' => $category->id, 'publish' => 1, 'order' => 2, 'is_brands' => 0]);
        $image = ProjectImage::create(['project_id' => $project->id, 'name' => 'gone.jpg', 'publish' => 1]);
        $layout = GridLayout::create(['key' => '1fr']);
        $grid = Grid::create(['project_id' => $project->id, 'layout_id' => $layout->id, 'order' => 1, 'publish' => 1]);
        GridElement::create(['grid_id' => $grid->id, 'project_image_id' => $image->id, 'position' => 0]);
        $homeLayout = HomeGridLayout::create(['key' => '1fr']);
        $homeGrid = HomeGrid::create(['layout_id' => $homeLayout->id, 'order' => 1, 'publish' => 1]);
        HomeGridElement::create(['grid_id' => $homeGrid->id, 'project_image_id' => $image->id, 'position' => 0, 'environment' => 'production', 'action' => 'keep']);
        $this->postJson("/api/projects/{$other->id}/relations", ['related_project_id' => $project->id])->assertCreated();

        $this->deleteJson("/api/projects/{$project->id}")->assertNoContent();

        $this->assertSame(0, Grid::count());
        $this->assertSame(0, GridElement::count());
        $this->assertSame(0, HomeGridElement::count());
        $this->assertSame(0, ProjectImage::count());
        $this->assertSame(0, $other->relations()->count());
        Mail::assertSent(ProjectRelationsDeleted::class);
    }

    public function test_project_relation_rejects_self_and_duplicates(): void
    {
        $a = $this->project(['name' => 'A', 'publish' => 1, 'order' => 1, 'is_brands' => 0]);
        $b = $this->project(['name' => 'B', 'publish' => 1, 'order' => 2, 'is_brands' => 0]);

        $this->postJson("/api/projects/{$a->id}/relations", ['related_project_id' => $a->id])->assertUnprocessable();
        $this->postJson("/api/projects/{$a->id}/relations", ['related_project_id' => $b->id])->assertCreated()->assertJsonPath('related.name', 'B');
        $this->postJson("/api/projects/{$a->id}/relations", ['related_project_id' => $b->id])->assertUnprocessable();
    }

    public function test_home_grid_staging_deploy_and_reset(): void
    {
        $layout = HomeGridLayout::create(['key' => '2x1fr']);
        $news = News::create(['title' => 'Neu', 'linkNewWindow' => 0]);

        $grids = $this->postJson('/api/home-grids', ['layout_id' => $layout->id])->assertCreated()->json();
        $gridId = $grids[0]['id'];

        $elementId = $this->postJson("/api/home-grids/{$gridId}/elements", ['position' => 0, 'news_id' => $news->id])
            ->assertCreated()->assertJsonPath('environment', 'development')->json('id');

        // Staged elements are not on the live home page yet
        $this->postJson('/api/home-grids/reset')->assertNoContent();
        $this->assertSame(0, HomeGridElement::count());

        $elementId = $this->postJson("/api/home-grids/{$gridId}/elements", ['position' => 0, 'news_id' => $news->id])->json('id');
        $this->postJson('/api/home-grids/deploy')->assertNoContent();
        $this->assertSame('production', HomeGridElement::find($elementId)->environment);

        // Deleting a live element only marks it until the next deploy
        $this->deleteJson("/api/home-grid-elements/{$elementId}")->assertNoContent();
        $this->assertSame('delete', HomeGridElement::find($elementId)->action);
        $this->getJson("/api/home-grids/{$gridId}/elements")->assertJsonCount(0);

        $this->postJson('/api/home-grids/reset')->assertNoContent();
        $this->assertSame('keep', HomeGridElement::find($elementId)->action);

        // News used on the home page cannot be deleted
        $this->deleteJson("/api/news/{$news->id}")->assertUnprocessable();
    }

    public function test_grid_elements_mark_images_as_used(): void
    {
        $project = $this->project(['name' => 'A', 'publish' => 1, 'order' => 1, 'is_brands' => 0]);
        $image = ProjectImage::create(['project_id' => $project->id, 'name' => 'a.jpg', 'publish' => 1]);
        $layout = GridLayout::create(['key' => '1fr']);

        $grids = $this->postJson("/api/projects/{$project->id}/grids", ['layout_id' => $layout->id])->assertCreated()->json();

        $elementId = $this->postJson("/api/grids/{$grids[0]['id']}/elements", ['position' => 0, 'project_image_id' => $image->id])
            ->assertCreated()->json('id');
        $this->assertSame(1, (int) $image->fresh()->is_grid);

        $this->deleteJson("/api/grid-elements/{$elementId}")->assertNoContent();
        $this->assertSame(0, (int) $image->fresh()->is_grid);
    }

    public function test_content_keys_are_restricted(): void
    {
        $this->getJson('/api/contents/keys')->assertOk()->assertJsonFragment(['key' => 'home']);
        $this->postJson('/api/contents', ['key' => 'home', 'text' => '<p>Hi</p>'])->assertCreated();
        $this->postJson('/api/contents', ['key' => 'whatever', 'text' => 'x'])->assertUnprocessable();
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
