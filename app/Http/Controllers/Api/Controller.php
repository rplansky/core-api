<?php

namespace Boitata\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a new JSON response from the application.
     * It wraps default JSON response to allow a future use of Transformers and Serializers.
     *
     * @param  string|array $data
     * @param  int          $status
     * @param  array        $headers
     * @param  int          $options
     *
     * @return JsonResponse
     */
    protected function respond($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($data, $status, $headers, $options);
    }
    /**
     * Return a new JSON response from the application with validation errors.
     *
     * @SWG\Definition(
     *   definition="ValidationErrors",
     *   type="object",
     *   required={"errors"},
     *   @SWG\Property(
     *     property="errors",
     *     description="List of human-readable validation error messages",
     *     type="array",
     *     @SWG\Items(type="string",example={"The name field is required.", "The age must be an integer."}),
     *   ),
     * )
     *
     * @SWG\Response(
     *   response="ValidationErrors",
     *   description="Human-readable validation errors",
     *   @SWG\Schema(ref="#/definitions/ValidationErrors")
     * ),
     *
     * @param  string|array $errors
     * @param  int          $status
     * @param  array        $headers
     * @param  int          $options
     *
     * @return JsonResponse
     */
    protected function respondWithErrors($errors = [], $status = 422, array $headers = [], $options = 0)
    {
        return $this->respond(compact('errors'), $status, $headers, $options);
    }
}
