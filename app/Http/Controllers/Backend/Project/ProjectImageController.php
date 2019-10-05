<?php
namespace App\Http\Controllers\Backend\Project;

use App\Services\MediaService;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\GridElement;
use App\Http\Resources\ProjectCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    protected $mediaService;
    protected $project;
    protected $projectImage;
    protected $gridElement;

    /**
     * Constructor
     * 
     * @param MediaService $mediaService
     * @param Project $project
     * @param ProjectImage $projectImage
     * @param GridElement $gridElement
     */

    public function __construct(
        MediaService $mediaService,
        Project $project,
        ProjectImage $projectImage,
        GridElement $gridElement
    )
    {
        $this->mediaService = $mediaService;
        $this->project      = $project;
        $this->projectImage = $projectImage;
        $this->gridElement  = $gridElement;
    }

    /**
     * Get all published records
     *
     * @param int $projectId
     * @return \Illuminate\Http\Response
     */

    public function get($projectId = NULL)
    {
        $projectImages = $this->projectImage
                              ->where('project_id', '=', $projectId)
                              ->where('publish', '=', 1)
                              ->orderBy('parent_id', 'ASC')
                              ->notInGrid()
                              ->get();

        return new ProjectCollection($projectImages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  str $filename
     * @return \Illuminate\Http\Response
     */
    public function unlink($filename)
    {
        // Delete image
        $image = $this->projectImage->where('name', $filename)->first();
        if ($image)
        {
            $image->delete();
        }

        // Delete grid element
        $gridElement = $this->gridElement->where('project_image_id', '=', $image->id)->first();
        if ($gridElement)
        {
            $gridElement->delete();
        }

        // Delete image from disk
        $this->mediaService->delete($filename);

        return response()->json('successfully deleted');
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $image = $this->projectImage->findOrFail($id);
        $image->publish = $image->publish == 0 ? 1 : 0;
        $image->save();
        return response()->json($image->publish);
    }

    /**
     * Update the order of the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        $images = $request->get('images');
        foreach($images as $i)
        {
            $image = $this->projectImage->find($i['id']);
            $image->order = $i['order'];
            $image->save(); 
        }
        return response()->json('successfully updated');
    }

    /**
     * Crop a grid image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function crop(Request $request)
    {
        $data = $request->get('data');

        // Crop the image
        $image = $this->mediaService->crop($data);

        if (isset($image['name']))
        {
            // Clone & adjust project image
            $projectImage = $this->projectImage->find($data['imageId']);
            $projectImage->is_grid = 0;
            $projectImage->parent_id = $projectImage->id;
            $projectImage->save();

            $projectImageCopy = $projectImage->replicate();
            $projectImageCopy->name = $image['name'];
            $projectImageCopy->is_crop = 1;
            $projectImageCopy->is_grid = 1;
            $projectImageCopy->save();

            // Clone & adjust grid element
            $gridElement = $this->gridElement->find($data['gridElementId']);
            $gridElement->project_image_id = $projectImageCopy->id;
            $gridElement->save();

            return response()->json($image);
        }

        return FALSE;
        
    }
}
