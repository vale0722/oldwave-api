<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesApiController extends Controller
{
    public function index(Request $request): array
    {
        return response()->api(
            Status::OK,
            CategoriesResource::collection(Category::enabled()->paginate())->toArray($request)
        );
    }
}
