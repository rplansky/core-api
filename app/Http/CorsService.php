<?php

namespace Boitata\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CorsService
{
    /**
     * Origins that can perform CORS requests.
     * Notice that app's own sub-domains are already allowed and does not need to be listed below.
     *
     * @var array
     */
    protected $allowedOrigins = [
        'https://boitata-publication-platform.github.io',
    ];

    /**
     * Verifies if request origin is an acceptable host to perform CORS requests and
     * adds proper headers to response, otherwise just return the unchanged response.
     *
     * @param Request               $request
     * @param Response|JsonResponse $response
     *
     * @return Illuminate\Http\Response
     */
    public function handle(Request $request, $response)
    {
        $origin = $this->getOrigin($request);

        if ($origin && $this->isOriginAllowed($origin)) {
            $response->header('Access-Control-Allow-Origin', $origin);
            $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            $response->header('Access-Control-Allow-Methods', 'GET, HEAD, PATCH, PUT, POST, DELETE, OPTIONS');
            $response->header('Vary', 'Origin');
            // https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Requests_with_credentials
            $response->header('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }

    /**
     * Retrieve the `Origin` header from request,
     * if it's different from the host being requested (foreign origin).
     * Otherwise, retrieves `false`.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getOrigin(Request $request)
    {
        $origin = $request->headers->get('Origin', false);

        if ($origin === $request->getSchemeAndHttpHost()) {
            return '';
        }

        return $origin;
    }

    /**
     * Verifies if given origin (merely a host) is allowed to perform CORS requests
     * on requested host.
     *
     * @param string $origin
     *
     * @return bool
     */
    protected function isOriginAllowed(string $origin)
    {
        $originWithoutPort = preg_replace('/:\d+/', '', $origin);

        return $this->isInternalOrigin($originWithoutPort)
            || in_array($originWithoutPort, $this->allowedOrigins);
    }

    /**
     * If Origin is a sub-domain of app's domain, then it's allowed.
     *
     * @param string $origin
     *
     * @return bool
     */
    protected function isInternalOrigin(string $origin)
    {
        $appUrl = trim(config('app.url'), '/');
        $parsedHost = preg_replace('/(https?:\/\/)?(www\.)?/', '', $appUrl);
        $parsedHost = preg_replace('/:\d+/', '', $parsedHost);

        $acceptedOrigins = '/^https?:\/\/(\w+\.)?'.$parsedHost.'$/i';

        return (bool) preg_match($acceptedOrigins, $origin);
    }
}
