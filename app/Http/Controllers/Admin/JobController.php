<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Job::orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Job::create($this->validated($request)), 201);
    }

    public function show(Job $job): JsonResponse
    {
        return response()->json($job);
    }

    public function update(Request $request, Job $job): JsonResponse
    {
        $job->update($this->validated($request));

        return response()->json($job);
    }

    public function destroy(Job $job): Response
    {
        $job->delete();

        return response()->noContent();
    }

    public function togglePublish(Job $job): JsonResponse
    {
        return $this->toggleFlag($job);
    }

    public function clone(Job $job): JsonResponse
    {
        $copy = $job->replicate();
        $copy->title = $job->title.' (Kopie)';
        $copy->publish = 0;
        $copy->save();

        return response()->json($copy, 201);
    }

    public function order(Request $request): Response
    {
        return $this->applyOrder($request, Job::class);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'text' => ['nullable', 'string'],
        ]);
    }
}
