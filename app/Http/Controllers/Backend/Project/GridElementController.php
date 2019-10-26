<?php
namespace App\Http\Controllers\Backend\Project;

use App\Models\GridElement;
use App\Models\ProjectImage;
use App\Http\Resources\GridCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GridElementController extends Controller
{
    protected $gridElement;
    protected $projectImage;

    public function __construct(
        GridElement $gridElement,
        ProjectImage $projectImage
    )
    {
        $this->gridElement = $gridElement;
        $this->projectImage = $projectImage;
    }

    /**
     * Get all records
     *
     * @param int $gridId
     * @return \Illuminate\Http\Response
     */

    public function get($gridId)
    {
        $gridElements = $this->gridElement
                              ->with('image.project')
                              ->with('news')
                              ->byGrid($gridId)
                              ->get();

        return new GridCollection($gridElements);
    }

    /**
     * Store a newly created image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeImage(Request $request)
    {
        $item = new GridElement([
            'grid_id'           => $request->get('grid_id'),
            'project_id'        => $request->get('project_id'),
            'project_image_id'  => $request->get('project_image_id'),
            'position'          => $request->get('position')
        ]);
        $item->save();

        // Mark an image as used
        $image = $this->projectImage->find($request->get('project_image_id'));
        $image->is_grid = 1;
        $image->save();

        return response()->json('success');
    }

    /**
     * Delete a grid image
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyImage($id)
    {
        $element = $this->gridElement->find($id);
        $imageId = $element->project_image_id;
        if ($element->delete())
        {
            // Mark an image as unused
            $image = $this->projectImage->find($imageId);
            $image->is_grid = 0;
            $image->save();
        }

        return response()->json('successfully deleted');
    }

    /**
     * Store a newly created article.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeArticle(Request $request)
    {
        $item = new GridElement([
            'grid_id'  => $request->get('grid_id'),
            'news_id'  => $request->get('news_id'),
            'position' => $request->get('position')
        ]);
        $item->save();
        return response()->json('success');
    }

    /**
     * Delete a grid article
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyArticle($id)
    {
        $element = $this->gridElement->find($id);
        $element->delete();
        return response()->json('successfully deleted');
    }
}
