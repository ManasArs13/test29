<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;

class CarModelController extends Controller
{
    public function index()
    {
        return CarModelResource::collection(CarModel::with('brand')->withCount('cars')->paginate(15));
    }
}
