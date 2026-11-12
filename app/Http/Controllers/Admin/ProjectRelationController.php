<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectRelation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ProjectRelationController extends Controller
{
    public function index(Project $project): JsonResponse
    {
        return response()->json($project->relations()->with('related')->get());
    }

    /**
     * Body: {"related_project_id": 12}
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'related_project_id' => [
                'required', 'integer', 'exists:projects,id',
                Rule::notIn([$project->id]),
                Rule::unique('project_relations')->where('project_id', $project->id),
            ],
        ]);

        $relation = $project->relations()->create($data);

        return response()->json($relation->load('related'), 201);
    }

    public function destroy(ProjectRelation $relation): Response
    {
        $relation->delete();

        return response()->noContent();
    }
}
