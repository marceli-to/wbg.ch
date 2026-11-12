<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    private array $created = [];

    protected function tearDown(): void
    {
        // MediaService writes to the real storage folder; clean up after ourselves
        foreach ($this->created as $name) {
            app(MediaService::class)->delete($name);
        }

        parent::tearDown();
    }

    public function test_image_upload_rejects_non_images(): void
    {
        $this->actingAs(User::factory()->create());

        $php = UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;');
        $this->postJson('/api/uploads/image', ['file' => $php])->assertUnprocessable();

        $disguised = UploadedFile::fake()->createWithContent('shell.jpg', '<?php echo 1;');
        $this->postJson('/api/uploads/image', ['file' => $disguised])->assertUnprocessable();

        $this->postJson('/api/uploads/document', ['file' => UploadedFile::fake()->image('a.png')])->assertUnprocessable();
    }

    public function test_image_upload_stores_source_and_thumbnail_with_safe_name(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/uploads/image', [
            'file' => UploadedFile::fake()->image('My Photo.final.PNG', 1200, 800),
        ])->assertCreated();

        $name = $response->json('name');
        $this->created[] = $name;

        $this->assertMatchesRegularExpression('/^[0-9a-f]{13}_my-photo-final\.png$/', $name);
        $this->assertFileExists(storage_path('app/public/media/images/source/'.$name));
        $this->assertFileExists(storage_path('app/public/media/images/processed/thumbs/'.$name));

        [$w, $h] = getimagesize(storage_path('app/public/media/images/processed/thumbs/'.$name));
        $this->assertSame([200, 200], [$w, $h]);

        // Sizes are generated on first request
        $this->get('/media/'.$name.'/sm')->assertOk();
        [$w] = getimagesize(storage_path('app/public/media/images/processed/sm/'.$name));
        $this->assertSame(500, $w);
    }
}
