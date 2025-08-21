<?php

namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CarResource",
 *     type="object",
 *     title="Car Resource",
 *     description="Car resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Car ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="brand",
 *         ref="#/components/schemas/BrandResource",
 *         description="Brand information"
 *     ),
 *     @OA\Property(
 *         property="car_model",
 *         ref="#/components/schemas/CarModelResource",
 *         description="Car model information"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         description="User information",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="year",
 *         type="integer",
 *         description="Year of manufacture",
 *         example=2020,
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="mileage",
 *         type="integer",
 *         description="Mileage in km",
 *         example=50000,
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="color",
 *         type="string",
 *         description="Car color",
 *         example="Red",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp",
 *         example="2024-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update timestamp",
 *         example="2024-01-01T12:00:00Z"
 *     )
 * )
 */
class CarResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'year' => $this->year,
            'mileage' => $this->mileage,
            'color' => $this->color,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'brand_id' => $this->brand_id,
            'car_model_id' => $this->car_model_id,

            'brand' => new BrandResource($this->whenLoaded('brand')),
            'carModel' => new CarModelResource($this->whenLoaded('carModel')),
        ];
    }
}
