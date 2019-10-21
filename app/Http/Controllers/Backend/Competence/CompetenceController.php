<?php

namespace App\Http\Controllers\Backend\Competence;

use App\Services\MediaService;
use App\Models\Competence;
use App\Models\CompetenceMedia;
use App\Http\Resources\CompetenceCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompetenceController extends Controller
{
    protected $mediaService;

    protected $competence;

    protected $competenceMedia;
    
    /**
     * Constructor
     * 
     * @param MediaService $mediaService
     * @param Competence $competence
     * @param CompetenceMedia $competenceMedia
     */

    public function __construct(
        MediaService $mediaService, 
        Competence $competence,
        CompetenceMedia $competenceMedia
    )
    {
        $this->mediaService     = $mediaService;
        $this->competence       = $competence;
        $this->competenceMedia  = $competenceMedia;
    }

    /**
     * Get all records
     *
     * @return \Illuminate\Http\Response
     */

    public function get()
    {
        $competences = $this->competence->with('category')
                                        ->orderBy('order', 'ASC')
                                        ->get();
                                        
        return new CompetenceCollection($competences);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {   
        $competence = new Competence([
            'title'         => $request->input('title'),
            'description'   => $request->input('description'),
            'category_id'   => $request->input('category_id') ? $request->input('category_id') : null,
        ]);
        $competence->save();

        if (!empty($request->media))
        {
            foreach($request->media as $i)
            {
                $media = new CompetenceMedia([
                    'competence_id' => $competence->id,
                    'name'          => $i['name'],
                    'caption'       => $i['caption'],
                    'publish'       => $i['publish'],
                ]);
                $media->save();
            }
        }

        return response()->json(['competenceId' => $competence->id]);
    }

    /**
     * Edit a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $competence = $this->competence->with('category')
                                       ->with('media')
                                       ->findOrFail($id);
        return response()->json($competence);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $competence = $this->competence->findOrFail($id);
        $competence->title       = $request->input('title');
        $competence->description = $request->input('description');
        $competence->category_id = $request->input('category_id') != 'NULL' ? $request->input('category_id') : null;
        $competence->save();

        if (!empty($request->media))
        {
            foreach($request->media as $i)
            {
                $image = $this->competenceMedia->updateOrCreate(
                    ['id' => $i['id']], 
                    [
                        'competence_id' => $competence->id,
                        'name'          => $i['name'],
                        'caption'       => $i['caption'],
                        'publish'       => $i['publish']
                    ]
                );
            }
        }

        return response()->json('successfully updated');
    }

    /**
     * Clone a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clone($id)
    {
        $competence = $this->competence->findOrFail($id);
        $competenceCopy = $competence->replicate();
        $competenceCopy->title    = $competence->title . ' (Kopie)';
        $competenceCopy->publish  = 0;
        $competenceCopy->save();
        return response()->json($competenceCopy);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $competence = $this->competence->findOrFail($id);
        $competence->publish = $competence->publish == 0 ? 1 : 0;
        $competence->save();
        return response()->json($competence->publish);
    }

    /**
     * Update the order of the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        $competences = $request->get('competences');
        foreach($competences as $competence)
        {
            $c = $this->competence->find($competence['id']);
            $c->order = $competence['order'];
            $c->save();
        }
        return response()->json('successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $competence = $this->competence->with('media')->find($id);
        
        if ($competence)
        {
            // Delete media
            if (isset($competence->media))
            {
                foreach($competence->media as $i)
                {
                    $this->mediaService->delete($i->name);
                    $i->delete();
                }
            }
            $competence->delete();
        }
        return response()->json('successfully deleted');
    }
}
