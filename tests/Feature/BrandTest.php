<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_list_of_brands_with_pagination()
    {
        // Создаем 20 брендов
        Brand::factory()->count(20)->create();

        $response = $this->getJson('/api/brands');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'car_models_count',
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
    public function it_includes_car_models_and_cars_counts()
    {
        $brand = Brand::factory()->create();

        // Создаем модели и автомобили для бренда
        $carModels = CarModel::factory()->count(3)->create(['brand_id' => $brand->id]);
        Car::factory()->count(5)->create(['brand_id' => $brand->id]);

        $response = $this->getJson('/api/brands');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $brand->id,
                'name' => $brand->name,
                'car_models_count' => 3,
                'cars_count' => 5
            ]);
    }

    #[Test]
    public function it_returns_empty_list_when_no_brands_exist()
    {
        $response = $this->getJson('/api/brands');

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
        Brand::factory()->count(5)->create();

        // Тестируем некорректные параметры
        $response = $this->getJson('/api/brands?page=abc&per_page=xyz');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 15
                ]
            ]);
    }

    #[Test]
    public function it_returns_correct_data_structure_for_brands()
    {
        $brand = Brand::factory()->create();

        $response = $this->getJson('/api/brands');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'car_models_count',
                        'cars_count',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonFragment([
                'id' => $brand->id,
                'name' => $brand->name
            ]);
    }

    #[Test]
    public function it_returns_zero_counts_when_no_related_models_exist()
    {
        $brand = Brand::factory()->create();

        $response = $this->getJson('/api/brands');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $brand->id,
                'car_models_count' => 0,
                'cars_count' => 0
            ]);
    }

    #[Test]
    public function it_does_not_require_authentication()
    {
        // Этот endpoint должен быть публичным
        Brand::factory()->count(3)->create();

        $response = $this->getJson('/api/brands');

        $response->assertStatus(200); // Не должно возвращать 401
    }
}
