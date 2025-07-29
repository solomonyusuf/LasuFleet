<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *     title="LASU Fleet Manager API",
 *     version="1.0.0",
 *     description="API documentation for the LASU fleet management system."
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
