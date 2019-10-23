<?php
namespace App\Http\Controllers\Backend\Project;

use App\Models\Project;
use App\Models\ProjectRelation;
use App\Http\Resources\ProjectCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectRelationController extends Controller
{
  protected $project;
  protected $projectRelation;

  /**
   * Constructor
   * 
   * @param Project $project
   * @param ProjectRelation $projectRelation
   */

  public function __construct(Project $project, ProjectRelation $projectRelation)
  {
    $this->project          = $project;
    $this->projectRelation  = $projectRelation;
  }

  /**
   * Get all records
   *
   * @param int $projectId
   * @return \Illuminate\Http\Response
   */

  public function get($projectId = NULL)
  {
    $relations = $this->projectRelation
                      ->with('related')
                      ->where('project_id', '=', $projectId)
                      ->get();

    return new ProjectCollection($relations);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  
  public function store(Request $request)
  {   
    $relation = new ProjectRelation([
      'project_id'         => $request->input('project_id'),
      'related_project_id' => $request->input('related_project_id'),
    ]);
    $relation->save();
    return response()->json(['relationId' => $relation->id]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function delete($id)
  {
    $relation = $this->projectRelation->findOrFail($id);
    $relation->delete();
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
    $relation = $this->projectRelation->findOrFail($id);
    $relation->publish = $relation->publish == 0 ? 1 : 0;
    $relation->save();
    return response()->json($relation->publish);
  }

  /**
   * Update the order of the resources.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $relations = $request->get('relations');
    foreach($relations as $i)
    {
      $relation = $this->projectRelation->find($i['id']);
      $relation->order = $i['order'];
      $relation->save(); 
    }
    return response()->json('successfully updated');
  }
}
