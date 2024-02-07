<?php
namespace App\Services;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class MenuService
{
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
    // Get projects
    $projects = $this->project->published()
                              ->with('category')
                              ->orderBy('order')
                              ->get()
                              ->groupBy('category.id');

    // Get project
    $project = $this->project->find($projectId);
    
    // Get categories
    $categories = $this->category->published()
                                 ->orderBy('order')
                                 ->get();

    // Menu
    $menu = [];
    foreach($categories as $k => $c)
    {
      $menu_items = [];

      if (isset($projects[$c->id]))
      {
        // Special case 'Panoptikum'
        if ($c->id == $this->category->panoptikumId)
        {
          foreach($projects[$c->id] as $p)
          {
            if ($p->subcategory_id)
            {
              $menu_items[$p->subcategory_id] = [
                'id'        => array_search($p->subcategory_id, $this->category->subcategories),
                'name'      => $this->category->subcategories[$p->subcategory_id],
                'slug'      => '/projekte/3/panoptikum/' . $p->subcategory_id . '/' . \Str::slug($this->category->subcategories[$p->subcategory_id]),
                'is-active' => $subcategoryId == $p->subcategory_id ? TRUE : FALSE,
              ];
            }
          }

          $menu_items = collect($menu_items)->sortBy('name')->toArray();
        }
        else
        {
          foreach($projects[$c->id] as $p)
          {
            $menu_items[] = [
              'id'        => $p->id,
              'name'      => $p->name,
              'slug'      => '/projekt/' . $p->id . '/' . \Str::slug($p->name),
              'is-active' => $projectId == $p->id ? TRUE : FALSE,
            ];
          }
        }

        $menu[] = [
          'category'  => $c->name,
          'id'        => $c->id,
          'slug'      => '/projekte/' . $c->id . '/' . \Str::slug($c->name),
          'is-active' => ($categoryId == $c->id || ($project && $project->category_id == $c->id)) ? TRUE : FALSE,
          'items'     => $menu_items
        ];
      }
    }
    return $menu;
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