<?php

namespace Boitata\Http\Controllers\Api;

use Boitata\Http\Controllers\Api\V1\IndexController as V1IndexController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController.
 *
 * Meta API actions.
 */
class IndexController extends Controller
{
    /**
     * Response for root ('/') of API.
     * It should report the URI for latest version of API.
     *
     * @return JsonResponse
     */
    public function root()
    {
        return $this->respond(
            [
                'name'    => 'Boitata API',
                'message' => 'Please use /v1 endpoint',
                'uri'     => action('\\'.V1IndexController::class.'@root'),
            ],
            Response::HTTP_MULTIPLE_CHOICES
        );
    }
}
