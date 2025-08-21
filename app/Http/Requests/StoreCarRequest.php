<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
