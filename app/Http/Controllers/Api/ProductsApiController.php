<?php

namespace App\Http\Controllers\Api;

use App\Events\RatingProductEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductsResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductsApiController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductsResource::collection(Product::paginate());
    }


    public function store(Request $request): JsonResponse
    {
        $product = new Product();
        $product->name = $request['name'];
        $product->brand = $request['brand'];
        $product->price = $request['price'];
        $product->city = $request['city'];
        $product->description = $request['description'];
        $product->discount = $request['discount'];
        $product->category()->associate($request['id']);
        $product->seller()->associate($request['id']);

        $product->save();

        return ProductsResource::make($product)->response()->setStatusCode(201);
    }


    public function show(Product $product): ProductsResource
    {
        RatingProductEvent::dispatch($product->id);
        return ProductsResource::make($product);
    }


    public function update(Request $request, Product $product): JsonResponse
    {
        $product->name = $request['name'];
        $product->brand = $request['brand'];
        $product->price = $request['price'];
        $product->city = $request['city'];
        $product->description = $request['description'];
        $product->discount = $request['discount'];
        $product->category()->associate($request['id']);
        $product->seller()->associate($request['id']);

        $product->save();

        return ProductsResource::make($product)->response()->setStatusCode(201);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(['status' => '201', 'message' => 'se ha eliminado correctamente el producto que has elegido']);
    }
}
