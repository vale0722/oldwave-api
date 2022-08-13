<?php

namespace App\Http\Controllers\Api;

use App\Actions\StoreOrUpdateItemAction;
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

    public function store(Request $request, StoreOrUpdateItemAction $action): JsonResponse
    {
        $item = $action->setData($request->validated())->execute()->getModel();

        return response()->api(
            Status::OK,
            ItemResource::make($item)->toArray($request)
        );
    }

    public function show(Request $request, Item $item): ItemsResource
    {
        RatingItemEvent::dispatch($item->getKey());

        return response()->api(
            Status::OK,
            ItemResource::make($item)->toArray($request)
        );
    }

    public function update(Request $request, Item $item, StoreOrUpdateItemAction $action): JsonResponse
    {
        $item = $action->setModel($item)
            ->setData($request->validated())
            ->execute()->getModel();

        return response()->api(
            Status::OK,
            ItemResource::make($item)->toArray($request)
        );
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
