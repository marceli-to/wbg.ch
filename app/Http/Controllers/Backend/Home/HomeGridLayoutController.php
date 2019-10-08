<?php
namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeGridLayout;
use App\Http\Resources\GridCollection;

use Illuminate\Http\Request;

class HomeGridLayoutController extends Controller
{
  protected $homeGridLayout;

  public function __construct(HomeGridLayout $homeGridLayout)
  {
    $this->homeGridLayout = $homeGridLayout;
  }

  /**
   * Fetch all layouts
   *
   * @return \Illuminate\Http\Response
   */
  public function fetch()
  {
    return new GridCollection($this->homeGridLayout->get());
  }
}
