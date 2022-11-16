<?php
namespace App\Http\Controllers\Backend\Job;
use App\Models\Job;
use App\Http\Resources\JobCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * Get all records
     *
     * @return \Illuminate\Http\Response
     */

    public function get()
    {
        $job = $this->job->orderBy('order', 'ASC')->get();
        return new JobCollection($job);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $job = new Job([
            'title' => $request->input('title'),
            'text' => $request->input('text'),
        ]);

        $job->save();
        return response()->json(['jobId' => $job->id]);
    }

    /**
     * Edit a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = $this->job->findOrFail($id);
        return response()->json($job);
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
        $job = $this->job->findOrFail($id);
        $job->title = $request->input('title');
        $job->text = $request->input('text');
        $job->save();
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
        $job = $this->job->findOrFail($id);
        $jobCopy = $job->replicate();
        $jobCopy->title = $job->title . ' (Kopie)';
        $jobCopy->publish = 0;
        $jobCopy->save();
        return response()->json($jobCopy);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $job = $this->job->findOrFail($id);
        $job->publish = $job->publish == 0 ? 1 : 0;
        $job->save();
        return response()->json($job->publish);
    }

    /**
     * Update the order of the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        $jobs = $request->get('jobs');
        foreach($jobs as $job)
        {
            $j = $this->job->find($job['id']);
            $j->order = $job['order'];
            $j->save();
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
    {   $job = $this->job->findOrFail($id);
        $job->delete();
        return response()->json('successfully deleted');
    }
}
