<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Booking Web App API",
 *     version="1.0.0",
 *     description="API for the Booking Web App"
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local development server"
 * )
 * @OA\PathItem(
 *     path="/api"
 * )
 */
abstract class Controller
{
    //
}
