<?php

namespace App\Http\Controllers\Api;

use App\Actions\StoreOrUpdateItemAction;
use App\Constants\Status;
use App\Events\RatingItemEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Items\ItemsIndexRequest;
use App\Http\Requests\Items\ItemsStoreRequest;
use App\Http\Resources\Api\ItemResource;
use App\Http\Resources\Api\ItemsResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemsApiController extends Controller
{
    public function index(ItemsIndexRequest $request): AnonymousResourceCollection
    {
        return ItemsResource::collection(Item::filter($request->validated())->paginate());
    }

    public function rating(): AnonymousResourceCollection
    {
        return ItemsResource::collection(Item::moreVisited()->limit(10)->get());
    }

    public function store(ItemsStoreRequest $request, StoreOrUpdateItemAction $action)
    {
        $item = $action->setData($request->validated())->execute()->getModel();

        return response()->api(
            Status::OK,
            ItemResource::make($item)->toArray($request)
        );
    }

    public function show(Request $request, Item $item): array
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
