<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Events\RatingItemEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Items\ItemsIndexRequest;
use App\Http\Resources\Api\ItemResource;
use App\Http\Resources\Api\ItemsResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsApiController extends Controller
{
    public function index(ItemsIndexRequest $request): array
    {
        return response()->api(
            Status::OK,
            ItemsResource::collection(Item::filter($request->validated())->paginate())->toArray($request)
        );
    }

    public function rating(Request $request): array
    {
        return response()->api(
            Status::OK,
            ItemsResource::collection(Item::moreVisited()->limit(10)->get())->toArray($request)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $item = new Item();
        $item->name = $request['name'];
        $item->brand = $request['brand'];
        $item->price = $request['price'];
        $item->city = $request['city'];
        $item->description = $request['description'];
        $item->discount = $request['discount'];
        $item->category()->associate($request['id']);
        $item->seller()->associate($request['id']);

        $item->save();

        return ItemsResource::make($item)->response()->setStatusCode(201);
    }

    public function show(Request $request, Item $item): ItemsResource
    {
        RatingItemEvent::dispatch($item->getKey());

        return response()->api(
            Status::OK,
            ItemResource::make($item)->toArray($request)
        );
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $item->name = $request['name'];
        $item->brand = $request['brand'];
        $item->price = $request['price'];
        $item->city = $request['city'];
        $item->description = $request['description'];
        $item->discount = $request['discount'];
        $item->category()->associate($request['id']);
        $item->seller()->associate($request['id']);

        $item->save();

        return ItemsResource::make($item)->response()->setStatusCode(201);
    }

    public function destroy(Item $item): JsonResponse
    {
        $item->delete();
        return response()->api(
            Status::OK,
            ['message' => 'se ha eliminado correctamente el item']
        );
    }
}
