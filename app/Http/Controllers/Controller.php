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
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use a bearer token to access protected endpoints",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 * )
 */
abstract class Controller
{
    //
}
