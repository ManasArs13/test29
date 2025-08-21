<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="StoreCarRequest",
 *     type="object",
 *     required={"brand_id", "car_model_id"},
 *     @OA\Property(
 *         property="brand_id",
 *         type="integer",
 *         description="Brand ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="car_model_id",
 *         type="integer",
 *         description="Car model ID",
 *         example=1
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
 *     )
 * )
 */
class StoreCarRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'exists:brands,id'],
            'car_model_id' => [
                'required',
                'exists:car_models,id',
                Rule::exists('car_models', 'id')->where('brand_id', $this->brand_id)
            ],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
