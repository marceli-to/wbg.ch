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

  public function boot($projectId = NULL, $categoryId = NULL)
  {
    $menu = [
      'projects'      => $this->getProjects($projectId,$categoryId),
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

  private function getProjects($projectId = NULL, $categoryId = NULL, $typeId = NULL)
  {
    // Get projects
    $projects = $this->project->published()
                              ->with('category')
                              ->get()
                              ->groupBy('category.id');
    
    // Get categories
    $categories = $this->category->published()
                                 ->orderBy('order')
                                 ->get();

    // Menu
    $menu = [];
    foreach($categories as $key => $category)
    {
      $menu[] = [
        'category' => $category->name,
        'id'       => $category->id,
        'projects' => $projects[$category->id]
      ];
    }
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