<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $brands = Brand::factory()->createMany([
            ['name' => 'Toyota'],
            ['name' => 'Honda'],
            ['name' => 'Ford'],
            ['name' => 'BMW'],
            ['name' => 'Mercedes-Benz'],
        ]);

        foreach ($brands as $brand) {
            CarModel::factory()->count(3)->create([
                'brand_id' => $brand->id,
            ]);
        }

        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            Car::factory()->count(rand(2, 5))->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
