<?php
namespace App\Http\Controllers\Backend\Project;

use App\Services\MediaService;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\GridElement;
use App\Models\HomeGridElement;
use App\Http\Resources\ProjectCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    protected $mediaService;
    protected $project;
    protected $projectImage;
    protected $gridElement;
    protected $homeGridElement;

    /**
     * Constructor
     * 
     * @param MediaService $mediaService
     * @param Project $project
     * @param ProjectImage $projectImage
     * @param GridElement $gridElement
     * @param HomeGridElement $homeGridElement
     */

    public function __construct(
        MediaService $mediaService,
        Project $project,
        ProjectImage $projectImage,
        GridElement $gridElement,
        HomeGridElement $homeGridElement
    )
    {
        $this->mediaService     = $mediaService;
        $this->project          = $project;
        $this->projectImage     = $projectImage;
        $this->gridElement      = $gridElement;
        $this->homeGridElement  = $homeGridElement;
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
                              ->get();

        return new ProjectCollection($projectImages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  str $filename
     * @return \Illuminate\Http\Response
     */
    public function delete($filename)
    {
        // Get image model by filename
        $image = $this->projectImage->where('name', $filename)->first();
        
        if ($image)
        {
            // Get all versions of the image
            if ($image->parent_id > 0)
            {
                $images = $this->projectImage->withCrop($image->parent_id)->get();
                if ($images)
                {
                    foreach($images as $image)
                    {
                        $image->delete();
                
                        // Delete grid element
                        if ($gridElement = $this->gridElement->where('project_image_id', '=', $image->id)->first())
                        {
                            $gridElement->delete();
                        }
            
                        // Delete home grid element
                        if ($homeGridElement = $this->homeGridElement->where('project_image_id', '=', $image->id)->first())
                        {
                            $homeGridElement->delete();
                        }
    
                        // Delete image from disk   
                        $this->mediaService->delete($image->name);
                    }
                }
            }
            else
            {
                $image->delete();
                
                // Delete grid element
                if ($gridElement = $this->gridElement->where('project_image_id', '=', $image->id)->first())
                {
                    $gridElement->delete();
                }
    
                // Delete home grid element
                if ($homeGridElement = $this->homeGridElement->where('project_image_id', '=', $image->id)->first())
                {
                    $homeGridElement->delete();
                }

                // Delete image from disk   
                $this->mediaService->delete($image->name);
            }
        }
        else
        {
            // Delete image from disk   
            $this->mediaService->delete($filename);
        }

        return response()->json('successfully deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  str $filename
     * @return \Illuminate\Http\Response
     */
    public function deleteCropped($filename)
    {
        // Get image model by filename
        $image = $this->projectImage->where('name', $filename)->first();
        
        if ($image)
        {
            $image->delete();
    
            // Delete grid element
            if ($gridElement = $this->gridElement->where('project_image_id', '=', $image->id)->first())
            {
                $gridElement->delete();
            }

            // Delete home grid element
            if ($homeGridElement = $this->homeGridElement->where('project_image_id', '=', $image->id)->first())
            {
                $homeGridElement->delete();
            }
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
     * Update the preview status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        $image = $this->projectImage->findOrFail($id);
        $image->is_preview = $image->is_preview == 0 ? 1 : 0;
        $image->save();
        return response()->json($image->is_preview);
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
            $projectImage->parent_id = ($projectImage->parent_id == 0) ? $projectImage->id : $projectImage->parent_id;
            $projectImage->save();

            $projectImageCopy = $projectImage->replicate();
            $projectImageCopy->name = $image['name'];
            $projectImageCopy->is_crop = 1;
            $projectImageCopy->is_preview = 0;
            $projectImageCopy->is_grid = 1;
            $projectImageCopy->save();

            // Clone & adjust grid element
            if ($data['isHomeGrid'])
            {
                $gridElement = $this->homeGridElement->find($data['gridElementId']);
            }
            else
            {
                $gridElement = $this->gridElement->find($data['gridElementId']);
            }

            $gridElement->project_image_id = $projectImageCopy->id;
            $gridElement->save();

            return response()->json($image);
        }

        return FALSE;
        
    }
}
