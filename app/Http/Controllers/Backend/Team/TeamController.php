<?php

namespace App\Http\Controllers\Backend\Team;

use App\Services\MediaService;
use App\Models\Team;
use App\Http\Resources\TeamCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected $mediaService;

    protected $team;
    
    /**
     * Constructor
     * 
     * @param MediaService $mediaService
     * @param Team $team
     */

    public function __construct(MediaService $mediaService, Team $team)
    {
        $this->mediaService = $mediaService;
        $this->team = $team;
    }

    /**
     * Get all records
     *
     * @return \Illuminate\Http\Response
     */

    public function get()
    {
        $team = $this->team->orderBy('order', 'ASC')->get();
        return new TeamCollection($team);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {   
        $team = new Team([
            'name'      =>  $request->input('name'),
            'firstname' =>  $request->input('firstname'),
            'role'      => $request->input('role'),
            'phone'     =>  $request->input('phone'),
            'email'     =>  $request->input('email'),
            'media'     =>  $request->input('media'),          
        ]);

        $team->save();
        return response()->json(['teamId' => $team->id]);
    }

    /**
     * Edit a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = $this->team->findOrFail($id);
        return response()->json($team);
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
        $team = $this->team->findOrFail($id);
        $team->name         = $request->input('name');
        $team->firstname    = $request->input('firstname');
        $team->phone        = $request->input('phone');
        $team->email        = $request->input('email');
        $team->media        = $request->input('media') ? $request->input('media') : NULL;
        $team->role         = $request->input('role');
        $team->save();
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
        $team = $this->team->findOrFail($id);
        $teamCopy           = $team->replicate();
        $teamCopy->name     = $team->name . ' (Kopie)';
        $teamCopy->media    = null;
        $teamCopy->publish  = 0;
        $teamCopy->save();
        return response()->json($teamCopy);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $team = $this->team->findOrFail($id);
        $team->publish = $team->publish == 0 ? 1 : 0;
        $team->save();
        return response()->json($team->publish);
    }

    /**
     * Update the order of the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        $team = $request->get('team');
        foreach($team as $t)
        {
            $team_member = $this->team->find($t['id']);
            $team_member->order = $t['order'];
            $team_member->save(); 
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
        $team = $this->team->find($id);
        if ($team)
        {
            if (isset($team->media) && $team->media != NULL)
            {
                $this->mediaService->delete($team->media);
            }
            $team->delete();
        }
        return response()->json('successfully deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  str $filename
     * @return \Illuminate\Http\Response
     */
    public function unlink($filename)
    {
        $team = $this->team->where('media', $filename)->first();
        if ($team)
        {
            $team->media = null;
            $team->save();
        }
        $this->mediaService->delete($filename);
        return response()->json('successfully deleted');
    }
}
