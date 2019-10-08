<?php

namespace App\Http\Controllers\Backend\Home;

use App\Services\MediaService;
use App\Models\HomeGrid;
use App\Models\HomeGridElement;
use App\Http\Resources\GridCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeGridController extends Controller
{
  protected $mediaService;

  protected $homeGrid;

  protected $homeGridElement;

  public function __construct(
    MediaService $service,
    HomeGrid $homeGrid,
    HomeGridElement $homeGridElement
  )
  {
    $this->mediaService = $service;
    $this->homeGrid = $homeGrid;
    $this->homeGridElement = $homeGridElement;
  }
  
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
      return 
        new GridCollection(
            $this->homeGrid->with('layout')
                            ->with('elements')
                            ->orderBy('order', 'ASC')
                            ->get()
        );
  }

  /**
   * Deploy changes to production
   *
   * @return \Illuminate\Http\Response
   */

  public function deploy()
  {
    $elements = $this->homeGridElement->toDelete()->orWhere->isDevelopment()->get();
    if (!empty($elements))
    {
      foreach($elements as $element)
      {
        if ($element->action == 'delete')
        {
          $element->delete();
        }
        if ($element->environment == 'development')
        {
          $element->environment = 'production';
          $element->save();
        }
      }
    }

    return response()->json('success');
  }

  /**
   * Reset changes
   *
   * @return \Illuminate\Http\Response
   */

  public function reset()
  {
    $elements = $this->homeGridElement->toDelete()->orWhere->isDevelopment()->get();
    if (!empty($elements))
    {
      foreach($elements as $element)
      {
        if ($element->action == 'delete')
        {
          $element->environment = 'production';
          $element->action = 'keep';
          $element->save();
        }
        if ($element->environment == 'development')
        {
          $element->delete();
        }
      }
    }

    return response()->json('success');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function store($layoutId)
  {
    $row = new HomeGrid([
      'layout_id' => $layoutId,
      'order'     => -1,
      'publish'   => 1,
    ]);
    $row->save();
    return new GridCollection($this->homeGrid->with('layout')->orderBy('order', 'ASC')->get());
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $this->homeGrid->find($id)->delete();
    $this->homeGridElement->where('grid_id', '=', $id)->delete();
    return response()->json('successfully deleted');
  }
}
