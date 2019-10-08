<?php
namespace App\Http\Controllers\Backend\Home;

use App\Services\GridService;
use App\Models\HomeGridElement;
use App\Http\Resources\GridCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeGridElementController extends Controller
{
  protected $homeGridElement;
  protected $gridService;

  public function __construct(HomeGridElement $homeGridElement, GridService $gridService)
  {
    $this->homeGridElement = $homeGridElement;
    $this->gridService = $gridService;
  }

  /**
   * Get all records
   *
   * @param int $gridId
   * @return \Illuminate\Http\Response
   */

  public function get($gridId)
  {
    return new GridCollection(
          $this->homeGridElement->with('projectimage.project')
                                ->with('news')
                                ->where('grid_id', '=', $gridId)
                                ->where('action', '=', 'keep')
                                ->get()
          );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $gridElement = new HomeGridElement([
      'grid_id'           => $request->get('grid_id'),
      'project_image_id'  => $request->get('project_image_id'),
      'news_id'           => $request->get('news_id'),
      'position'          => $request->get('position'),
      'environment'       => 'development',
    ]);

    $gridElement->save();
    return response()->json('success');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $gridElement = $this->homeGridElement->find($id);
    if ($gridElement->environment == 'production')
    {
      $gridElement->action = 'delete';
      $gridElement->save();
    }
    else
    {
      $gridElement->delete();
    }
    
    return response()->json('successfully deleted');
  }
}
