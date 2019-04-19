<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="ProductsByCategories",
 *     description="API documentation for the app"
 * )
 *
 * @OA\PathItem(path="/api")
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="L5 Swagger OpenApi dynamic host server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="passport",
 *     type="oauth2",
 *     scheme={"http", "https"},
 *     bearerFormat="bearer"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
