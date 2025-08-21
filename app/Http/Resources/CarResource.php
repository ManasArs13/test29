<?php

namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Car */
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
