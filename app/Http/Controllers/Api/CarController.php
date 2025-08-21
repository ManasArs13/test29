<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;

/**
 * Контроллер для работы с автомобилями
 *
 * @group Управление автомобилями
 *
 * @authenticated
 */
class CarController extends Controller
{
    /**
     * Получить список автомобилей пользователя
     *
     * Возвращает пагинированный список автомобилей, принадлежащих аутентифицированному пользователю.
     *
     * @queryParam page Номер страницы (default: 1) Example: 1
     * @queryParam per_page Количество элементов на странице (default: 15) Example: 15
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return CarResource::collection(Car::with('brand', 'carModel')->paginate(15));
    }

    /**
     * Создать новый автомобиль
     *
     * Создает новый автомобиль для аутентифицированного пользователя.
     *
     * @bodyParam brand_id integer required ID марки автомобиля. Example: 1
     * @bodyParam car_model_id integer required ID модели автомобиля. Example: 1
     * @bodyParam year integer Год выпуска. Example: 2020
     * @bodyParam mileage integer Пробег (км). Example: 50000
     * @bodyParam color string Цвет автомобиля. Example: Red
     *
     *
     * @param StoreCarRequest $request
     * @return CarResource
     */
    public function store(StoreCarRequest $request)
    {
        $car = auth()->user()->cars()->create($request->validated());

        return new CarResource($car->load(['brand', 'carModel']));
    }

    /**
     * Возвращает подробную информацию об указанном автомобиле.
     *
     * @urlParam car integer required ID автомобиля. Example: 1
     *
     * @param Car $car
     * @return CarResource
     */
    public function show(Car $car)
    {
        $this->authorize('view', $car);

        return new CarResource($car);
    }

    /**
     * Обновляет информацию об указанном автомобиле.
     *
     * @urlParam car integer required ID автомобиля. Example: 1
     *
     * @bodyParam brand_id integer ID марки автомобиля. Example: 1
     * @bodyParam car_model_id integer ID модели автомобиля. Example: 1
     * @bodyParam year integer Год выпуска. Example: 2020
     * @bodyParam mileage integer Пробег (км). Example: 50000
     * @bodyParam color string Цвет автомобиля. Example: Blue
     *
     *
     * @param UpdateCarRequest $request
     * @param Car $car
     * @return CarResource
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        $this->authorize('update', $car);

        $car->update($request->validated());

        return new CarResource($car);
    }

    /**
     * Удаляет указанный автомобиль.
     *
     * @urlParam car integer required ID автомобиля. Example: 1
     *
     * @param Car $car
     * @return JsonResponse
     */
    public function destroy(Car $car)
    {
        $this->authorize('delete', $car);

        $car->delete();

        return response()->json('', 204);
    }
}
