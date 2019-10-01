<?php
namespace App\Http\Controllers\Backend\Project;

use App\Services\MediaService;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Http\Resources\ProjectCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    protected $mediaService;

    protected $project;
    
    protected $projectImage;

    /**
     * Constructor
     * 
     * @param MediaService $mediaService
     * @param Project $project
     * @param ProjectImage $projectImage
     */

    public function __construct(
        MediaService $mediaService,
        Project $project,
        ProjectImage $projectImage
    )
    {
        $this->mediaService    = $mediaService;
        $this->project      = $project;
        $this->projectImage = $projectImage;
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
        $image = $this->projectImage->where('name', $filename)->first();
        if ($image)
        {
            $image->delete();
        }
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
}
