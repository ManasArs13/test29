<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель модели автомобиля
 *
 * @property int $id ID марки
 * @property string $name Название марки
 * @property \Illuminate\Support\Carbon $created_at Дата создания
 * @property \Illuminate\Support\Carbon $updated_at Дата обновления
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\brand[] $carModels Марка
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Car[] $cars Автомобили
 *
 */
class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand_id',
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
     * Получить автомобили
     *
     * @return HasMany
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
