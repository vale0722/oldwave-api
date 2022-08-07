<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoriesApiController extends Controller
{

    public function index(): AnonymousResourceCollection
    {
        return CategoriesResource::collection(Category::paginate());
    }


    public function store(Request $request): JsonResponse
    {
        $category = new Category();
        $category->name = $request['name'];
        $category->save();

        return CategoriesResource::make($category)->response()->setStatusCode(201);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $category->name = $request->name;
        $category->save();

        return CategoriesResource::make($category)->response()->setStatusCode(201);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(['status' => '201', 'message' => 'se ha eliminado correctamente el genero que has elegido']);
    }
}
