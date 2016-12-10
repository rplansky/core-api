<?php

namespace Boitata\Http\Controllers\Api\V1;

use File;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Log;
use TestCase;

class IndexControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function testShouldGetRoot()
    {
        $expected = [
            'name'          => 'Boitata API',
            'message'       => 'API is healthy',
            'documentation' => action('\\'.IndexController::class.'@documentation'),
        ];
        $this->action('GET', '\\'.IndexController::class.'@root');

        $this->assertResponseStatus(200);
        $this->seeJson($expected);
    }

    public function testShouldNotGetDocumentationWhenNotGenerated()
    {
        // skip throwing errors for testing
        $this->app->instance('env', 'local');

        $documentationPath = storage_path('app/public-api-documentation.json');
        $expected = [
            'message' => 'Under Maintenance',
        ];

        File::shouldReceive('exists')
            ->once()
            ->with($documentationPath)
            ->andReturn(false);

        Log::shouldReceive('warning')
            ->once();

        $this->action('GET', '\\'.IndexController::class.'@documentation');

        $this->assertResponseStatus(500);
        $this->seeJson($expected);
    }

    public function testShouldGetDocumentation()
    {
        $documentationPath = storage_path('app/public-api-documentation.json');
        $fileContent = '{"swagger": "content"}';
        $expected = ['swagger' => 'content'];

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

        $this->action('GET', '\\'.IndexController::class.'@documentation');

        $this->assertResponseOk();
        $this->seeJson($expected);
    }
}
