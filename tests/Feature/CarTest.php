<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $this->token = auth()->login($this->user);
    }

    #[Test]
    public function it_returns_paginated_list_of_user_cars()
    {
        // Создаем автомобили для текущего пользователя
        Car::factory()->count(10)->create(['user_id' => $this->user->id]);
        // И для другого пользователя (не должны отображаться)
        Car::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/cars');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'brand_id',
                        'car_model_id',
                        'year',
                        'mileage',
                        'color'
                    ]
                ],
                'links',
                'meta'
            ])
            ->assertJson(['meta' => ['total' => 15]]);
    }

    #[Test]
    public function it_creates_new_car_for_authenticated_user()
    {
        $brand = Brand::factory()->create();
        $carModel = CarModel::factory()->create(['brand_id' => $brand->id]);

        $carData = [
            'brand_id' => $brand->id,
            'car_model_id' => $carModel->id,
            'year' => 2020,
            'mileage' => 50000,
            'color' => 'Red'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/cars', $carData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'brand_id',
                    'car_model_id',
                    'year',
                    'mileage',
                    'color'
                ]
            ])
            ->assertJson([
                'data' => [
                    'year' => 2020,
                    'mileage' => 50000,
                    'color' => 'Red'
                ]
            ]);

        $this->assertDatabaseHas('cars', [
            'user_id' => $this->user->id,
            'brand_id' => $brand->id,
            'car_model_id' => $carModel->id
        ]);
    }

    #[Test]
    public function it_returns_car_details()
    {
        $car = Car::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/cars/{$car->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'brand_id',
                    'car_model_id',
                    'year',
                    'mileage',
                    'color'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $car->id
                ]
            ]);
    }

    #[Test]
    public function it_forbids_viewing_other_users_car()
    {
        $otherUser = User::factory()->create();
        $otherUserCar = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/cars/{$otherUserCar->id}");

        $response->assertStatus(403)
            ->assertJson(['message' => 'This action is unauthorized.']);
    }

    #[Test]
    public function it_updates_user_car()
    {
        $car = Car::factory()->create(['user_id' => $this->user->id]);
        $newBrand = Brand::factory()->create();
        $newCarModel = CarModel::factory()->create(['brand_id' => $newBrand->id]);

        $updateData = [
            'brand_id' => $newBrand->id,
            'car_model_id' => $newCarModel->id,
            'color' => 'Blue',
            'mileage' => 60000
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson("/api/cars/{$car->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'color' => 'Blue',
                    'mileage' => 60000
                ]
            ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'color' => 'Blue',
            'mileage' => 60000
        ]);
    }

    #[Test]
    public function it_forbids_updating_other_users_car()
    {
        $otherUser = User::factory()->create();
        $otherUserCar = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson("/api/cars/{$otherUserCar->id}", [
            'color' => 'Blue'
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function it_deletes_user_car()
    {
        $car = Car::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/cars/{$car->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }

    #[Test]
    public function it_forbids_deleting_other_users_car()
    {
        $otherUser = User::factory()->create();
        $otherUserCar = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/cars/{$otherUserCar->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('cars', ['id' => $otherUserCar->id]);
    }
}
