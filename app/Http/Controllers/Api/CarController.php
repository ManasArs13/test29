<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        return CarResource::collection(Car::with('brand', 'carModel')->paginate(15));
    }

    public function store(StoreCarRequest $request)
    {
        $car = auth()->user()->cars()->create($request->validated());

        return new CarResource($car->load(['brand', 'carModel']));
    }

    public function show(Car $car)
    {
        $this->authorize('view', $car);

        return new CarResource($car);
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $this->authorize('update', $car);

        $car->update($request->validated());

        return new CarResource($car);
    }

    public function destroy(Car $car)
    {
        $this->authorize('delete', $car);

        $car->delete();

        return response()->json('', 204);
    }
}
