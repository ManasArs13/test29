<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_list_of_car_models_with_pagination()
    {
        // Создаем 20 моделей автомобилей
        CarModel::factory()->count(20)->create();

        $response = $this->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'brand' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at'
                        ],
                        'cars_count',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ])
            ->assertJsonCount(15, 'data'); // По умолчанию 15 на страницу
    }

    #[Test]
    public function it_returns_empty_list_when_no_car_models_exist()
    {
        $response = $this->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'total' => 0,
                    'current_page' => 1
                ]
            ])
            ->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_handles_invalid_pagination_parameters_gracefully()
    {
        CarModel::factory()->count(5)->create();

        // Тестируем некорректные параметры
        $response = $this->getJson('/api/car-models?page=abc&per_page=xyz');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 15
                ]
            ]);
    }

    #[Test]
    public function it_returns_correct_data_structure_for_car_models()
    {
        $brand = Brand::factory()->create();
        $carModel = CarModel::factory()->create(['brand_id' => $brand->id]);

        $response = $this->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'brand' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at'
                        ],
                        'cars_count',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonFragment([
                'id' => $carModel->id,
                'name' => $carModel->name
            ]);
    }

    #[Test]
    public function it_returns_zero_cars_count_when_no_cars_exist()
    {
        $carModel = CarModel::factory()->create();

        $response = $this->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $carModel->id,
                'cars_count' => 0
            ]);
    }

    #[Test]
    public function it_does_not_require_authentication()
    {
        // Этот endpoint должен быть публичным
        CarModel::factory()->count(3)->create();

        $response = $this->getJson('/api/car-models');

        $response->assertStatus(200); // Не должно возвращать 401
    }

    #[Test]
    public function it_includes_brand_data_correctly()
    {
        $brand = Brand::factory()->create(['name' => 'Test Brand']);
        $carModel = CarModel::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Test Model'
        ]);

        $response = $this->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $carModel->id,
                'name' => 'Test Model',
            ]);
    }
}
