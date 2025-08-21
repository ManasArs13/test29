<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;

/**
 * Контроллер для работы с марками автомобилей
 *
 * @group Управление марками автомобилей
 */
class BrandController extends Controller
{
    /**
     * Получить список марок автомобилей
     *
     * Возвращает пагинированный список всех марок автомобилей с количеством моделей и автомобилей для каждой марки.
     *
     * @queryParam page Номер страницы (default: 1) Example: 1
     * @queryParam per_page Количество элементов на странице (default: 15) Example: 15
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return BrandResource::collection(Brand::withCount('carModels', 'cars')->paginate(15));
    }
}
