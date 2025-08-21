<?php

namespace App\Http\Resources;

use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CarModelResource",
 *     type="object",
 *     title="Car Model Resource",
 *     description="Car model resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Car model ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Car model name",
 *         example="Camry"
 *     ),
 *     @OA\Property(
 *         property="brand",
 *         ref="#/components/schemas/BrandResource",
 *         description="Brand information"
 *     ),
 *     @OA\Property(
 *         property="cars_count",
 *         type="integer",
 *         description="Number of cars for this model",
 *         example=10
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
class CarModelResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'cars_count' => $this->whenCounted('cars'),
        ];
    }
}
