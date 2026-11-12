<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Client::orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Client::create($this->validated($request)), 201);
    }

    public function show(Client $client): JsonResponse
    {
        return response()->json($client);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        $client->update($this->validated($request));

        return response()->json($client);
    }

    public function destroy(Client $client): Response
    {
        $client->delete();

        return response()->noContent();
    }

    public function togglePublish(Client $client): JsonResponse
    {
        return $this->toggleFlag($client);
    }

    public function clone(Client $client): JsonResponse
    {
        $copy = $client->replicate();
        $copy->name = $client->name.' (Kopie)';
        $copy->publish = 0;
        $copy->save();

        return response()->json($copy, 201);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
        ]);
    }
}
