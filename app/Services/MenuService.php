<?php
namespace App\Services;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class MenuService
{
  private const CACHE_KEY = 'menu.projects';

  // Menu item active class
  protected $active = 'is-active';

  // Models
  protected $project;
  protected $category;

  public function __construct(
    Project $project,
    Category $category
  )
  {
    $this->project = $project;
    $this->category = $category;
  }

  public function boot($projectId = NULL, $categoryId = NULL, $subcategoryId = NULL)
  {
    $menu = [
      'projects'      => $this->getProjects($projectId,$categoryId,$subcategoryId),
      'about'         => $this->getAbout(),
    ];
    return $menu;
  }

  /**
   * Build project menu
   * 
   * @param int $projectId
   * @param int $categoryId
   * @param int $categoryTypeId
   */

  private function getProjects($projectId = NULL, $categoryId = NULL, $subcategoryId = NULL)
  {
    $menu = [];
    foreach ($this->projectTree() as $c)
    {
      $menu_items = [];

      if ($c['id'] == Category::PANOPTIKUM_ID)
      {
        // Special case 'Panoptikum': list subcategories instead of projects
        foreach ($c['projects'] as $p)
        {
          if ($p['subcategory_id'])
          {
            $name = Category::SUBCATEGORIES[$p['subcategory_id']];
            $menu_items[$p['subcategory_id']] = [
              'id'        => $p['subcategory_id'],
              'name'      => $name,
              'slug'      => '/projekte/3/panoptikum/' . $p['subcategory_id'] . '/' . \Str::slug($name),
              'is-active' => $subcategoryId == $p['subcategory_id'],
            ];
          }
        }

        $menu_items = collect($menu_items)->sortBy('name')->toArray();
      }
      else
      {
        foreach ($c['projects'] as $p)
        {
          $menu_items[] = [
            'id'        => $p['id'],
            'name'      => $p['name'],
            'slug'      => '/projekt/' . $p['id'] . '/' . \Str::slug($p['name']),
            'is-active' => $projectId == $p['id'],
          ];
        }
      }

      $menu[] = [
        'category'  => $c['name'],
        'id'        => $c['id'],
        'slug'      => '/projekte/' . $c['id'] . '/' . \Str::slug($c['name']),
        'is-active' => $categoryId == $c['id'],
        'items'     => $menu_items
      ];
    }
    return $menu;
  }

  /**
   * Published categories (that have published projects) with their
   * published projects. Cached until a project or category changes.
   */
  private function projectTree(): array
  {
    return Cache::rememberForever(self::CACHE_KEY, function () {
      $projects = $this->project->published()
                                ->orderBy('order')
                                ->get(['id', 'name', 'category_id', 'subcategory_id'])
                                ->groupBy('category_id');

      return $this->category->published()
                            ->orderBy('order')
                            ->get(['id', 'name'])
                            ->filter(fn ($c) => isset($projects[$c->id]))
                            ->map(fn ($c) => [
                              'id'       => $c->id,
                              'name'     => $c->name,
                              'projects' => $projects[$c->id]->map->only('id', 'name', 'subcategory_id')->values()->all(),
                            ])
                            ->values()
                            ->all();
    });
  }

  public static function flush(): void
  {
    Cache::forget(self::CACHE_KEY);
  }

  /**
   * Build about menu
   * 
   */

  private function getAbout()
  {
    $menu_items[] = [
      'name'      => 'Über uns',
      'slug'      => 'ueber-uns',
      'route'     => 'page.about',
      'is-parent' => FALSE,
      'is-active' => Route::currentRouteName() == 'page.about' ? TRUE : FALSE
    ];

    $menu_items[] = [
      'name'      => 'Jobs',
      'slug'      => 'jobs',
      'route'     => 'page.jobs',
      'is-parent' => FALSE,
      'is-active' => Route::currentRouteName() == 'page.jobs' ? TRUE : FALSE
    ];

    $menu_items[] = [
      'name'      => 'Auszeichnungen',
      'slug'      => 'auszeichnungen',
      'route'     => 'page.awards',
      'is-parent' => FALSE,
      'is-active' => Route::currentRouteName() == 'page.awards' ? TRUE : FALSE
    ];

    $menu_items[] = [
      'name'      => 'Vorträge',
      'slug'      => 'vortraege',
      'route'     => 'page.lectures',
      'is-parent' => FALSE,
      'is-active' => Route::currentRouteName() == 'page.lectures' ? TRUE : FALSE
    ];

    $menu = [
      'name'      => 'Büro',
      'slug'      => 'buero',
      'route'     => '',
      'is-parent' => TRUE,
      'is-active' => 
        Route::currentRouteName() == 'page.about' ||
        Route::currentRouteName() == 'page.jobs' ||
        Route::currentRouteName() == 'page.awards' ||
        Route::currentRouteName() == 'page.lectures'
          ? TRUE : FALSE,
      'items' => $menu_items
    ];

    return $menu;  
  }
}