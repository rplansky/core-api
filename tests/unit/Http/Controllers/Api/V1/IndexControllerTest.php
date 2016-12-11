<?php

namespace Boitata\Http\Controllers\Api\V1;

use File;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Log;
use TestCase;
use WithFramework;

class IndexControllerTest extends TestCase
{
    use WithoutMiddleware, WithFramework;

    public function testShouldGetRoot()
    {
        // Set
        $expected = [
            'name'          => 'Boitata API',
            'message'       => 'API is healthy',
            'documentation' => action('\\'.IndexController::class.'@documentation'),
        ];

        // Act
        $this->action('GET', '\\'.IndexController::class.'@root');

        // Assert
        $this->assertResponseStatus(200);
        $this->seeJson($expected);
    }

    public function testShouldNotGetDocumentationWhenNotGenerated()
    {
        // Set
        // skip throwing errors for testing
        $this->app->instance('env', 'local');

        $documentationPath = storage_path('app/public-api-documentation.json');
        $expected = [
            'message' => 'Under Maintenance',
        ];

        // Expect
        File::shouldReceive('exists')
            ->once()
            ->with($documentationPath)
            ->andReturn(false);

        Log::shouldReceive('warning')
            ->once();

        // Act
        $this->action('GET', '\\'.IndexController::class.'@documentation');

        // Assert
        $this->assertResponseStatus(500);
        $this->seeJson($expected);
    }

    public function testShouldGetDocumentation()
    {
        // Set
        $documentationPath = storage_path('app/public-api-documentation.json');
        $fileContent = '{"swagger": "content"}';
        $expected = ['swagger' => 'content'];

        // Expect
        File::shouldReceive('exists')
            ->once()
            ->with($documentationPath)
            ->andReturn(true);

        File::shouldReceive('get')
            ->once()
            ->with($documentationPath)
            ->andReturn($fileContent);

        Log::shouldReceive('warning')
            ->never();

        Log::shouldReceive('error');

        // Act
        $this->action('GET', '\\'.IndexController::class.'@documentation');

        // Assert
        $this->assertResponseOk();
        $this->seeJson($expected);
    }
}
