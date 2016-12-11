<?php

namespace Boitata\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TestCase;
use WithFramework;

class AuthenticateWithApiTokenTest extends TestCase
{
    use WithFramework;

    /**
     * @dataProvider getWrongAuthHeaders
     */
    public function testWrongAuthenticationsOnApi($authHeader, $expected)
    {
        // Set
        config(['api.auth_token' => 'v4l1d-t0k3n']);
        $request = new Request();
        $request->headers->set('authorization', $authHeader);
        $response = new JsonResponse();

        $middleware = new AuthenticateWithApiToken();

        // Act
        $this->response = $middleware->handle($request, function () use ($response) {
            return $response;
        });

        // Assert
        $this->assertResponseStatus(401);
        $this->assertJsonResponse(['message' => $expected]);
    }

    /**
     * @dataProvider getRightAuthHeaders
     */
    public function testRightAuthenticationsOnApi($authHeader)
    {
        // Set
        config(['api.auth_token' => 'v4l1d-t0k3n']);
        $request = new Request();
        $request->headers->set('authorization', $authHeader);
        $response = new JsonResponse();

        $middleware = new AuthenticateWithApiToken();

        // Act
        $this->response = $middleware->handle($request, function () use ($response) {
            return $response;
        });

        // Assert
        $this->assertResponseOk();
        $this->assertEquals($response, $this->response);
        $this->assertNull($request->header('authorization'));
    }

    public function getWrongAuthHeaders()
    {
        return [
            'null auth header'                                                       => [
                null,
                'Authentication required',
            ],
            'valid token but invalid type'                                           => [
                'bearer '.base64_encode('v4l1d-t0k3n:'),
                'Invalid credentials',
            ],
            'valid type, valid token but not encoded nor concatenated with password' => [
                'Basic v4l1d-t0k3n',
                'Invalid credentials',
            ],
            'valid type, valid token but not encoded'                                => [
                'Basic v4l1d-t0k3n:',
                'Invalid credentials',
            ],
            'valid type, invalid token'                                              => [
                'Basic '.base64_encode('invalid:'),
                'Invalid credentials',
            ],
            'valid token and type but with more characters after'                    => [
                'bearer '.base64_encode('v4l1d-t0k3n:').' invalid',
                'Invalid credentials',
            ],
        ];
    }

    public function getRightAuthHeaders()
    {
        return [
            'lowercase type'      => [
                'basic '.base64_encode('v4l1d-t0k3n:'),
            ],
            'uppercase type'      => [
                'BASIC '.base64_encode('v4l1d-t0k3n:'),
            ],
            'scrambled case type' => [
                'BaSiC '.base64_encode('v4l1d-t0k3n:'),
            ],
        ];
    }
}
