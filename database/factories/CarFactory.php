<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition()
    {
        return [
            'year' => $this->faker->randomNumber(),
            'mileage' => $this->faker->randomNumber(),
            'color' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'brand_id' => Brand::factory(),
            'user_id' => User::factory(),
            'car_model_id' => CarModel::factory(),
        ];
    }
}
