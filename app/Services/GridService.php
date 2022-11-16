<?php
namespace App\Services;

use App\Models\GridElement;
use App\Models\HomeGridElement;
use Illuminate\Http\Request;

class GridService
{
    // Models
    protected $homeGridElement;
    protected $gridElement;

    public function __construct(GridElement $gridElement, HomeGridElement $homeGridElement)
    {
        $this->gridElement = $gridElement;
        $this->homeGridElement = $homeGridElement;
    }

    /**
     * Check if the requested news is grid element (Home)
     * @param int $newsId
     */
    public function isGridNews($newsId)
    {
        $isGrid = $this->homeGridElement->where('news_id', '=', $newsId)->get()->first();
        return $isGrid ? TRUE : FALSE;
    }

    /**
     * Check if the requested image is grid element (Home, Projects)
     * @param int $imageId
     */
    public function isGridImage($imageId)
    {
        $isGridHome     = $this->homeGridElement->where('project_image_id', '=', $imageId)->get()->first();
        $isGridProject  = $this->gridElement->where('project_image_id', '=', $imageId)->get()->first();
        return ($isGridHome || $isGridProject) ? TRUE : FALSE;
    }

    public function exists($position, $gridId)
    {
        $element = $this->homeGridElement->where('position', '=', $position)
                                         ->where('grid_id', '=', $gridId)
                                         ->get()
                                         ->first();

        return ($element || $element) ? $element : FALSE;
    }
}