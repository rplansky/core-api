<?php

namespace Boitata\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticateWithApiToken
{
    /**
     * Run the request middleware.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$authorization = $request->header('authorization')) {
            return $this->unauthorized('Authentication required');
        }

        if (!$this->authenticate($authorization)) {
            return $this->unauthorized('Invalid credentials');
        }

        // application does not need to know about tokens
        $request->headers->remove('authorization');

        return $next($request);
    }

    /**
     * In order to authenticate to the API, the type must be 'Basic'
     * and token must match the one set on config.
     * E.g.: `Basic d2h5IGFyZSB5b3UgZGVjcmlwdGluZyB0aGlzPyA7UA==`.
     *
     * @param string $authorization Authorization header from request
     *
     * @return bool
     */
    protected function authenticate(string $authorization) : bool
    {
        $parts = explode(' ', $authorization);
        $type = $parts[0] ?? null;
        $token = $parts[1] ?? null;

        return count($parts) === 2 &&
            preg_match('/^basic$/i', $type) &&
            $this->validateToken($token);
    }

    /**
     * Validate token using http basic pattern: `<username>:<password>`,
     * encoded with base64 and where <username> represents the token
     * and <password> must be blank. (colon is required).
     *
     * @param string $token
     *
     * @return bool
     */
    protected function validateToken(string $token) : bool
    {
        $validToken = base64_encode(config('api.auth_token').':');

        return $token === $validToken;
    }

    /**
     * Respond with an authorization error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function unauthorized(string $message) : JsonResponse
    {
        return response()->json(compact('message'), 401);
    }
}
