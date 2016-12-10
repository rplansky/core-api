<?php

namespace Boitata\Console\Commands;

use File;
use Kameleon\Context\Config;
use Mockery as m;
use TestCase;

class ApiDocumentationTest extends TestCase
{
    public function testShouldHandleCommandWithSuccess()
    {
        // Set
        $storeConfig = m::mock(Config::class);
        $command = m::mock(
            ApiDocumentation::class.'[info,warn,error,swaggerScan,define]'
        );
        $command->shouldAllowMockingProtectedMethods();

        $path1 = storage_path('app/public-api-documentation.json');
        $scan1 = '{"scan": "response1"}';

        config([
            'app' => [
                'url'   => 'http://www.boitata.dev',
                'debug' => true,
            ],
            'api' => [
                'version' => '2.3.82',
                'prefix'  => 'public/utils/api',
            ],
            'job_tracker' => [
                'products' => [],
            ],
        ]);

        $this->app->instance(Config::class, $storeConfig);

        // Expectations
        $command->shouldReceive('swaggerScan')
            ->once()
            ->with(
                ['app']
            )->andReturn($scan1);

        $command->shouldReceive('warn')
            ->once()
            ->with("File already exists, overwriting. ($path1)");

        $command->shouldReceive('info')
            ->once()
            ->with("API documentation file generated successfully. ($path1)");

        $command->shouldReceive('error')
            ->never();

        $command->shouldReceive('define')
            ->once()
            ->with('API_HOST', 'www.boitata.dev');

        $command->shouldReceive('define')
            ->once()
            ->with('API_SCHEME', 'http');

        $command->shouldReceive('define')
            ->once()
            ->with('API_VERSION', '2.3.82');

        $command->shouldReceive('define')
            ->once()
            ->with('API_BASE_PATH', '/public/utils/api/v2');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_HOST', 'www.boitata.dev');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_SCHEME', 'http');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_VERSION', '1.0.0');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_BASE_PATH', '/boitata/api/v1');

        File::shouldReceive('exists')
            ->once()
            ->with($path1)
            ->andReturn(true);

        File::shouldReceive('put')
            ->once()
            ->with($path1, $scan1)
            ->andReturn(123);

        // Actions
        $result = $command->handle();

        // Assertions
        $this->assertTrue($result);
    }

    public function testShouldHandleCommandWithFailure()
    {
        // Set
        $storeConfig = m::mock(Config::class);
        $command = m::mock(
            ApiDocumentation::class.'[info,warn,error,define,swaggerScan]'
        );
        $command->shouldAllowMockingProtectedMethods();

        $filePath1 = storage_path('app/public-api-documentation.json');
        $scanResponse1 = '{"scan": "response1"}';

        config([
            'app' => [
                'url'   => 'http://localhost',
                'debug' => false,
            ],
            'api' => [
                'version' => '1.4',
                'prefix'  => 'api',
            ],
            'job_tracker' => [
                'products' => [],
            ],
        ]);

        $this->app->instance(Config::class, $storeConfig);

        // Expectations
        $command->shouldReceive('swaggerScan')
            ->once()
            ->with(
                ['app']
            )->andReturn($scanResponse1);

        $command->shouldReceive('warn')
            ->never();

        $command->shouldReceive('info')
            ->never();

        $command->shouldReceive('error')
            ->once()
            ->with("Failed to generate documentation file. ($filePath1)");

        $command->shouldReceive('define')
            ->once()
            ->with('API_HOST', 'localhost');

        $command->shouldReceive('define')
            ->once()
            ->with('API_SCHEME', 'https');

        $command->shouldReceive('define')
            ->once()
            ->with('API_VERSION', '1.4');

        $command->shouldReceive('define')
            ->once()
            ->with('API_BASE_PATH', '/api/v1');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_HOST', 'localhost');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_SCHEME', 'https');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_VERSION', '1.0.0');

        $command->shouldReceive('define')
            ->once()
            ->with('BOITATA_API_BASE_PATH', '/boitata/api/v1');

        File::shouldReceive('exists')
            ->once()
            ->with($filePath1)
            ->andReturn(false);

        File::shouldReceive('put')
            ->once()
            ->with($filePath1, $scanResponse1)
            ->andReturn(0);

        // Actions
        $result = $command->handle();

        // Assertions
        $this->assertFalse($result);
    }
}
