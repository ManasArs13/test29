<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;

/**
 * Контроллер для работы с моделями автомобилей
 *
 * @group Управление моделями автомобилей
 */
class CarModelController extends Controller
{
    /**
     * Получить список моделей автомобилей
     *
     * Возвращает пагинированный список всех моделей автомобилей с количеством автомобилей и брендом.
     *
     * @queryParam page Номер страницы (default: 1) Example: 1
     * @queryParam per_page Количество элементов на странице (default: 15) Example: 15
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return CarModelResource::collection(CarModel::with('brand')->withCount('cars')->paginate(15));
    }
}
