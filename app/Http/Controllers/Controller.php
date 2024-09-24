<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @method JsonResponse respondWithToken(string $token, mixed $user, int $statusCode = 200)
 * @method JsonResponse respondWithEmptyData(int $statusCode = 200)
 */
abstract class Controller
{
    //
}
