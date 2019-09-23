<?php
namespace App\Http\Controllers\Backend\Project;

use App\Http\Controllers\Controller;
use App\Models\GridLayout;
use App\Http\Resources\GridCollection;

use Illuminate\Http\Request;

class GridLayoutController extends Controller
{
  protected $gridLayout;

  public function __construct(GridLayout $gridLayout)
  {
    $this->gridLayout = $gridLayout;
  }

  /**
   * Get all layouts
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new GridCollection($this->gridLayout->get());
  }
}
