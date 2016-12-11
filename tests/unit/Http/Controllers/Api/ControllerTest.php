<?php

namespace Boitata\Http\Controllers\Api;

use Mockery as m;
use TestCase;
use WithFramework;

class ControllerTest extends TestCase
{
    use WithFramework;

    public function testShouldRespondWithDefaultParameters()
    {
        // Set
        $controller = m::mock(Controller::class);
        $data = [
            'user' => [
                'name'    => 'John',
                'surName' => 'Snow',
            ],
        ];

        // Act
        $this->response = $this->callProtected($controller, 'respond', [$data]);

        // Assert
        $this->assertResponseOk();
        $this->assertJsonResponse($data);
    }

    public function testShouldRespondWithParametersPassedIn()
    {
        // Set
        $controller = m::mock(Controller::class);
        $data = [
            'user' => [
                'name'    => 'John',
                'surName' => 'Snow',
            ],
        ];
        $headers = ['Accept' => 'text/json-123'];

        // Act
        $this->response = $this->callProtected($controller, 'respond', [$data, 201, ['Accept' => 'text/json-123']]);

        // Assert
        $this->assertResponseStatus(201);
        $this->assertJsonResponse($data);
        $this->assertEquals($headers['Accept'], $this->response->headers->get('accept'));
    }

    public function testShouldRespondWithErrorsUsingDefaultParameters()
    {
        // Set
        $controller = m::mock(Controller::class);
        $errors = [
            'user' => [
                'name' => 'The name field is required.',
            ],
        ];

        // Act
        $this->response = $this->callProtected($controller, 'respondWithErrors', [$errors]);

        // Assert
        $this->assertResponseStatus(422);
        $this->assertJsonResponse(compact('errors'));
    }

    public function testShouldRespondWithErrorsUsingParametersPassedIn()
    {
        // Set
        $controller = m::mock(Controller::class);
        $errors = [
            'user' => [
                'name' => 'The name field is required.',
            ],
        ];
        $headers = ['Accept' => 'text/json-123'];

        // Act
        $this->response = $this->callProtected($controller, 'respondWithErrors', [$errors, 403, $headers]);

        // Assert
        $this->assertResponseStatus(403);
        $this->assertJsonResponse(compact('errors'));
        $this->assertEquals($headers['Accept'], $this->response->headers->get('accept'));
    }
}
