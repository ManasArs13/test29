<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Справочник автомобилий API"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="api_key",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * ),
 * @OA\PathItem(path="/api/")
 */
abstract class Controller
{
    use AuthorizesRequests;
}
