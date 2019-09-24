<?php

namespace App\Http\Controllers\Backend\Project;

use App\Services\MediaService;
use App\Models\ProjectImage;
use App\Models\Grid;
use App\Models\GridElement;
use App\Http\Resources\GridCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GridController extends Controller
{
    protected $mediaService;
    protected $grid;
    protected $gridElement;
    protected $projectImage;

    public function __construct(
        MediaService $service,
        Grid $grid, 
        GridElement $gridElement,
        ProjectImage $projectImage
    )
    {
        $this->mediaService = $service;
        $this->grid = $grid;
        $this->gridElement = $gridElement;
        $this->projectImage = $projectImage;
    }
    
    /**
     * Get all grids by project
     *
     * @param int $projectId
     * @return \Illuminate\Http\Response
     */
    public function get($projectId)
    {
        return $this->_fetch($projectId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $projectId
     * @param int $layoutId
     * @return \Illuminate\Http\Response
     */
    public function store($projectId, $layoutId)
    {
        $grid = new Grid([
            'project_id' => $projectId,
            'layout_id'  => $layoutId,
            'order'      => 999,
            'publish'    => 1,
        ]);

        $grid->save();
        return $this->_fetch($projectId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Mark images as unused
        $images = $this->gridElement->where('grid_id', '=', $id)->get();
        foreach($images as $i)
        {
            $img = $this->projectImage->find($i->project_image_id);
            $img->is_grid = 0;
            $img->save();
        }
        
        $this->grid->find($id)->delete();
        $this->gridElement->where('grid_id', '=', $id)->delete();
        return response()->json('successfully deleted');
    }

    /**
     * Update the order of the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        $grids = $request->get('grids');
        foreach($grids as $grid)
        {
            $g = $this->grid->find($grid['id']);
            $g->order = $grid['order'];
            $g->save(); 
        }
        return response()->json('successfully updated');
    }

    /**
     * Fetch database records by project
     *
     * @param int $project_id
     */

    protected function _fetch($projectId)
    {
        $grids = $this->grid
            ->byProject($projectId)
            ->with('layout')
            ->orderBy('order', 'ASC')
            ->get();
        return new GridCollection($grids);
    }
}
