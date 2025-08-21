<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель марки автомобиля
 *
 * @property int $id ID марки
 * @property string $name Название марки
 * @property \Illuminate\Support\Carbon $created_at Дата создания
 * @property \Illuminate\Support\Carbon $updated_at Дата обновления
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarModel[] $carModels Модели автомобилей
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Car[] $cars Автомобили
 * @property-read int|null $car_models_count Количество моделей
 * @property-read int|null $cars_count Количество автомобилей
 */
class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Получить автомобили марки
     *
     * @return HasMany
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    /**
     * Получить модели автомобилей для марки
     *
     * @return HasMany
     */
    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
