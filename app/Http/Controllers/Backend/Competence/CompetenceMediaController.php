<?php
namespace App\Http\Controllers\Backend\Competence;

use App\Services\MediaService;
use App\Models\Competence;
use App\Models\CompetenceMedia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompetenceMediaController extends Controller
{
    protected $mediaService;

    protected $competence;
    
    protected $competenceMedia;

    /**
     * Constructor
     * 
     * @param MediaService $mediaService
     * @param Competence $competence
     */

    public function __construct(
        MediaService $mediaService,
        Competence $competence,
        CompetenceMedia $competenceMedia
    )
    {
        $this->mediaService    = $mediaService;
        $this->competence      = $competence;
        $this->competenceMedia = $competenceMedia;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  str $filename
     * @return \Illuminate\Http\Response
     */
    public function unlink($filename)
    {
        $image = $this->competenceMedia->where('name', $filename)->first();
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
        $image = $this->competenceMedia->findOrFail($id);
        $image->publish = $image->publish == 0 ? 1 : 0;
        $image->save();
        return response()->json($image->publish);
    }
}
