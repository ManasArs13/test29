<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format: Bearer <token>"
 * )
 */

/**
 * Контроллер для аутентификации и управления пользователями
 *
 * @group Аутентификация
 */
class AuthController extends Controller
{
    /**
     * Конструктор контроллера
     *
     * Устанавливает middleware для аутентификации, кроме метода login.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Регистрация нового пользователя
     *
     * Создает нового пользователя и возвращает access token.
     *
     * @bodyParam name string required Имя пользователя. Example: John Doe
     * @bodyParam email string required Email пользователя. Example: john@example.com
     * @bodyParam password string required Пароль пользователя (мин. 8 символов). Example: password123
     * @bodyParam password_confirmation string required Подтверждение пароля. Example: password123
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = auth()->login($user);

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Registration failed. Please try again later.'
            ], 500);
        }
    }

    /**
     * Аутентификация пользователя
     *
     * Авторизует пользователя и возвращает access token.
     *
     * @bodyParam email string required Email пользователя. Example: john@example.com
     * @bodyParam password string required Пароль пользователя. Example: password123
     *
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Выход из системы
     *
     * Деактивирует текущий access token пользователя.
     *
     * @header Authorization Bearer {access_token}
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Обновление access token
     *
     * Обновляет истекший access token с помощью refresh token.
     *
     * @header Authorization Bearer {access_token}
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Форматирование ответа с токеном
     *
     * @param string $token JWT токен
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Получение информации о текущем пользователе
     *
     * Возвращает данные аутентифицированного пользователя с количеством его автомобилей.
     *
     * @header Authorization Bearer {access_token}
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json(auth()->user()->loadCount('cars'));
    }
}
