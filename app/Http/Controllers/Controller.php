<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Annotations\OpenApi;

/**
 *
 * @OA\Info(
 *     title="API Comprasnet Contratos",
 *     version="1.0",
 *     @OA\Contact (
 *                  email="heles.junior@agu.gov.br"
 *                 ),
 *     @OA\License (
 *                  name="Apache 2.0",
 *                  url= "http://www.apache.org/licenses/LICENSE-2.0.html"
 *                 ),
 *     ),
 *  @OA\Server(
 *      url="http://contratos.comprasnet.gov.br",
 *      description="API Server"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
