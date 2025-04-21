<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Podcast Platform API",
 *     version="1.0.0",
 *     description="API documentation for the Podcast platform"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Localhost API Server"
 * )
 */
abstract class Controller
{
    //
}
