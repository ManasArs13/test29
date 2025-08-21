<?php

namespace App\Models;

use App\Policies\CarPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

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

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
