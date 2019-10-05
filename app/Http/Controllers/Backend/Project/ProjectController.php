<?php

namespace App\Http\Controllers\Backend\Project;

use App\Services\MediaService;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Http\Resources\ProjectCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
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
        $this->mediaService  = $mediaService;
        $this->project       = $project;
        $this->projectImage  = $projectImage;
    }

    /**
     * Get one record
     *
     * @param id $projectId
     * @return \Illuminate\Http\Response
     */

    public function get($projectId)
    {
        $project = $this->project->find($projectId);
        return response()->json($project);
    }

    /**
     * Get all records
     *
     * @return \Illuminate\Http\Response
     */

    public function all()
    {
        $projects = $this->project->with('category')
                                  ->with('client')
                                  ->with('images')
                                  ->orderBy('order', 'ASC')
                                  ->get();
        return new ProjectCollection($projects);
    }

    /**
     * Get all records with constraints
     *
     * @return \Illuminate\Http\Response
     */

    public function fetch($publish = 0, $order = 'ASC')
    {
        $projects = $this->project->where('publish', '=', $publish)
                                  ->orderBy('name', $order)
                                  ->with('images')
                                  ->get();
        return new ProjectCollection($projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {   
        $project = new Project([
            'name'              => $request->input('name'),
            'principal'         => $request->input('principal'),
            'description'       => $request->input('description'),
            'meta_description'  => $request->input('meta_description'),
            'category_id'       => $request->input('category_id') ? $request->input('category_id') : null,
            'client_id'         => $request->input('client_id') ? $request->input('client_id') : null,
        ]);
        $project->save();

        if (!empty($request->images))
        {
            foreach($request->images as $i)
            {
                $image = new ProjectImage([
                    'project_id'    => $project->id,
                    'name'          => $i['name'],
                    'caption'       => $i['caption'],
                    'publish'       => $i['publish'],
                ]);
                $image->save();
            }
        }

        return response()->json(['projectId' => $project->id]);
    }

    /**
     * Edit a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = $this->project->with('category')
                                 ->with('client')
                                 ->with('originalImages')
                                 ->findOrFail($id);
        return response()->json($project);
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
        $project = $this->project->findOrFail($id);
        $project->name              = $request->input('name');
        $project->principal         = $request->input('principal');
        $project->description       = $request->input('description');
        $project->meta_description  = $request->input('meta_description');
        $project->category_id       = $request->input('category_id') ? $request->input('category_id') : null;
        $project->client_id         = $request->input('client_id') ? $request->input('client_id') : null;
        $project->save();

        if (!empty($request->images))
        {
            foreach($request->images as $i)
            {
                $image = $this->projectImage->updateOrCreate(
                    ['id' => $i['id']], 
                    [
                        'project_id'    => $project->id,
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
        $project = $this->project->findOrFail($id);
        $projectCopy = $project->replicate();
        $projectCopy->name          = $project->name . ' (Kopie)';
        $projectCopy->principal     = $project->principal;
        $projectCopy->category_id   = $project->category_id;
        $projectCopy->client_id     = $project->client_id;
        $projectCopy->publish       = 0;
        $projectCopy->save();
        return response()->json($projectCopy);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $project = $this->project->findOrFail($id);
        $project->publish = $project->publish == 0 ? 1 : 0;
        $project->save();
        return response()->json($project->publish);
    }

    /**
     * Update the order of the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        $projects = $request->get('projects');
        foreach($projects as $project)
        {
            $c = $this->project->find($project['id']);
            $c->order = $project['order'];
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
        $project = $this->project->with('images')->find($id);
        if ($project)
        {
            // Delete media
            if (isset($project->images))
            {
                foreach($project->images as $i)
                {
                    $this->mediaService->delete($i->name);
                    $i->delete();
                }
            }
            $project->delete();
        }
        return response()->json('successfully deleted');
    }
}
