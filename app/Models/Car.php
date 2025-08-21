<?php

namespace App\Models;

use App\Policies\CarPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

/**
 * Модель автомобиля
 *
 * @property int $id ID автомобиля
 * @property int $user_id ID владельца автомобиля
 * @property int $brand_id ID марки автомобиля
 * @property int $car_model_id ID модели автомобиля
 * @property int|null $year Год выпуска
 * @property int|null $mileage Пробег (км)
 * @property string|null $color Цвет автомобиля
 * @property \Illuminate\Support\Carbon $created_at Дата создания
 * @property \Illuminate\Support\Carbon $updated_at Дата обновления
 *
 * @property-read \App\Models\User $user Владелец автомобиля
 * @property-read \App\Models\Brand $brand Марка автомобиля
 * @property-read \App\Models\CarModel $carModel Модель автомобиля
 */
#[UsePolicy(CarPolicy::class)]
class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'mileage',
        'color',
        'brand_id',
        'car_model_id',
        'user_id',
    ];

    /**
     * Получить марку автомобиля
     *
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Получить модель автомобиля
     *
     * @return BelongsTo
     */
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    /**
     * Получить владельца автомобиля
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
