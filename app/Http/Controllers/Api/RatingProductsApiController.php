<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\RatingProductsResource;
use App\Models\RatingProduct;
use Illuminate\Http\Request;

class RatingProductsApiController extends Controller
{
    public function index()
    {
        return RatingProductsResource::collection(RatingProduct::all());
    }
}
