<?php

namespace Boitata\Http\Controllers\Api\V1;

use Boitata\Http\Controllers\Api\Controller;
use File;
use Illuminate\Http\JsonResponse;
use Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * @SWG\Swagger(
 *   schemes={API_SCHEME},
 *   host=API_HOST,
 *   basePath=API_BASE_PATH,
 *   consumes={"application/json"},
 *   produces={"application/json"},
 *   @SWG\Info(
 *     title="Boitata API Documentation",
 *     description="This documentation lists all available API endpoints and how to interact with them.",
 *     version=API_VERSION,
 *     @SWG\ExternalDocumentation(
 *       description="UI for understanding the documentation.",
 *       url="https://boitata-publication-platform.github.io/boitata-core-api-doc/"
 *     ),
 *   ),
 * )
 *
 * @SWG\Tag(
 *   name="API",
 *   description="Meta information about the API",
 * )
 */
class IndexController extends Controller
{
    /**
     * API name that will be exposed by root action.
     *
     * @var string
     */
    protected $apiName = 'Boitata API';

    /**
     * Path to Swagger's JSON documentation file.
     *
     * @var string
     */
    protected $documentationPath = 'app/public-api-documentation.json';

    /**
     * Response for root ('/') of V1 API.
     *
     * @SWG\Get(
     *   path="/",
     *   summary="Health Check",
     *   description="Verifies if the API is in a healthy state and provides an URL for the `JSON documentation`.",
     *   tags={"API"},
     *   @SWG\Response(
     *     response=\Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *     description="API is healthy",
     *     @SWG\Schema(
     *       @SWG\Property(
     *         property="name",
     *         type="string",
     *         description="API name",
     *       ),
     *       @SWG\Property(
     *         property="message",
     *         type="string",
     *         description="Health Check descriptive status",
     *       ),
     *       @SWG\Property(
     *         property="documentation",
     *         type="string",
     *         description="Documentation URI",
     *       ),
     *     ),
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="unexpected errors",
     *   )
     * )
     *
     * @return JsonResponse
     */
    public function root()
    {
        $documentationAction = action('\\'.static::class.'@documentation');

        return $this->respond([
            'name'          => $this->apiName,
            'message'       => 'API is healthy',
            'documentation' => $documentationAction,
        ]);
    }

    /**
     * Swagger documentation for V1 API.
     *
     * @SWG\Get(
     *   path="/documentation",
     *   summary="Documentation",
     *   description="Retrieve the swagger documentation that you are currently reading.",
     *   tags={"API"},
     *   @SWG\Response(
     *     response=\Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *     description="Swagger documentation"
     *   ),
     *   @SWG\Response(
     *     response=\Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
     *     description="Under Maintenance"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="unexpected errors",
     *   )
     * )
     *
     * @return Response
     */
    public function documentation()
    {
        $documentationPath = storage_path($this->documentationPath);

        if (false === File::exists($documentationPath)) {
            Log::warning(
                'Swagger JSON file was not generated, please generate it!',
                compact('documentationPath')
            );

            return abort(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Under Maintenance'
            );
        }

        return response(
            File::get($documentationPath),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
