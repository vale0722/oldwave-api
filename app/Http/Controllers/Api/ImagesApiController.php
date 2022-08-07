<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ImagesResource;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ImagesApiController extends Controller
{
    public function index($id): AnonymousResourceCollection
    {
        return ImagesResource::collection(Image::class)->where('product_id', $id);
    }


    public function store(Request $request): JsonResponse
    {
        $image = Image::actions()->storeOrUpdate($request->validated())->getModel();
        return ImagesResource::make($image)->response()->setStatusCode(201);
    }

    public function update(Request $request, Image $image): JsonResponse
    {

        $image = Image::actions()->storeOrUpdate($request->validated(), $image);
        return ImagesResource::make($image)->response()->setStatusCode(201);
    }

    public function destroy(Image $image): JsonResponse
    {
        $image->delete();
        return response()->json(['status' => '201', 'message' => 'se ha eliminado correctamente la imagen que has elegido']);
    }
}
