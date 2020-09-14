<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Annotations\OpenApi;

/**
 * @OA\Info(
 *     title="API Comprasnet",
 *     version="1.0",
 *     @OA\Contact (
 *                  email="heles.junior@agu.gov.br"
 *                 )
 *     )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
