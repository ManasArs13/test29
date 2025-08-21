<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

/**
 * Форма запроса для регистрации пользователя
 *
 * @bodyParam name string required Имя пользователя. Example: John Doe
 * @bodyParam email string required Email пользователя. Example: john@example.com
 * @bodyParam password string required Пароль пользователя (мин. 8 символов). Example: password123
 * @bodyParam password_confirmation string required Подтверждение пароля. Example: password123
 */
class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
