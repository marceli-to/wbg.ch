<?php

namespace App\Http\Controllers\Backend\Client;

use App\Services\MediaService;
use App\Models\Client;
use App\Http\Resources\ClientCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $mediaService;

    protected $client;
    
    /**
     * Constructor
     * 
     * @param MediaService $mediaService
     * @param Client $client
     */

    public function __construct(MediaService $mediaService, Client $client)
    {
        $this->mediaService = $mediaService;
        $this->client = $client;
    }

    /**
     * Get all records
     *
     * @return \Illuminate\Http\Response
     */

    public function get()
    {
        $clients = $this->client->orderBy('name', 'ASC')->get();
        return new ClientCollection($clients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {   
        $client = new Client([
            'name'          => $request->input('name'),
            'location'      => $request->input('location'),
            'website'       => $request->input('website') ? \AppHelper::addScheme($request->input('website')) : NULL,
            'project_id'    => $request->input('project_id'),          
        ]);

        $client->save();
        return response()->json(['clientId' => $client->id]);
    }

    /**
     * Edit a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = $this->client->findOrFail($id);
        return response()->json($client);
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
        $client = $this->client->findOrFail($id);
        $client->name       = $request->input('name');
        $client->location   = $request->input('location');
        $client->website    = $request->input('website') ? \AppHelper::addScheme($request->input('website')) : NULL;
        $client->project_id = $request->input('project_id');
        $client->save();
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
        $client = $this->client->findOrFail($id);
        $clientCopy = $client->replicate();
        $clientCopy->name       = $client->name . ' (Kopie)';
        $clientCopy->location   = $client->location;
        $clientCopy->publish    = 0;
        $clientCopy->save();
        return response()->json($clientCopy);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $client = $this->client->findOrFail($id);
        $client->publish = $client->publish == 0 ? 1 : 0;
        $client->save();
        return response()->json($client->publish);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = $this->client->find($id);
        if ($client)
        {
            $client->delete();
        }
        return response()->json('successfully deleted');
    }
}
