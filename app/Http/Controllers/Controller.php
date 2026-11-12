<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    /**
     * Flip a 0/1 flag on a model and return the new value.
     */
    protected function toggleFlag(Model $model, string $attribute = 'publish'): JsonResponse
    {
        $model->{$attribute} = $model->{$attribute} ? 0 : 1;
        $model->save();

        return response()->json(['value' => $model->{$attribute}]);
    }

    /**
     * Persist a new sort order. Body: {"items": [{"id": 1, "order": 0}, …]}
     *
     * @param  class-string<Model>  $modelClass
     */
    protected function applyOrder(Request $request, string $modelClass): Response
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer'],
            'items.*.order' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($data, $modelClass) {
            foreach ($data['items'] as $item) {
                $modelClass::whereKey($item['id'])->update(['order' => $item['order']]);
            }
        });

        // Sort order is part of the cached navigation menu
        MenuService::flush();

        return response()->noContent();
    }
}
