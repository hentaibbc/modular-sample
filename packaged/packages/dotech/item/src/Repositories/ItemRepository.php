<?php

namespace Dotech\Item\Repositories;

use Dotech\Item\Models\Item;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ItemRepository
{
    public function validate(Request $request): ?MessageBag
    {
        $validator = Validator::make($request->all(), [
            'items.*.id'        => 'required|exists:items,id',
        ]);

        return $validator->fails() ? $validator->errors() : null;
    }

    public function getItems(array $ids): Collection
    {
        $ids = Arr::wrap($ids);

        return Item::query()
            ->whereIn('id', $ids)
            ->get();
    }
}