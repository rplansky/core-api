<?php

namespace Boitata\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Mockery as m;
use TestCase;
use WithFramework;

class HandlerTest extends TestCase
{
    use WithFramework;

    public function testShouldAddCorsToApiRequests()
    {
        // Set
        $request = m::mock(Request::class.'[is]');
        $handler = $this->app->make(Handler::class);

        // skip throwing errors for testing
        $this->app->instance('env', 'local');

        $origin = 'http://bar.foo.com.br';
        config(['app.url' => 'http://www.foo.com.br']);
        $request->headers->set('Host', 'www.foo.com.br');
        $request->headers->set('Origin', $origin);
        $apiPrefix = 'public/api';

        config(['api.prefix' => $apiPrefix]);

        // Act
        $response = $handler->render($request, new Exception());

        // Assert
        $headers = $response->headers;
        $this->assertTrue($headers->has('Access-Control-Allow-Origin'));
        $this->assertTrue($headers->has('Access-Control-Allow-Credentials'));
        $this->assertTrue($headers->has('Access-Control-Allow-Headers'));
        $this->assertTrue($headers->has('Vary'));
        $this->assertEquals(
            $origin,
            $headers->get('Access-Control-Allow-Origin')
        );
        $this->assertEquals(
            'true',
            $headers->get('Access-Control-Allow-Credentials')
        );
        $this->assertEquals(
            'Content-Type, Authorization',
            $headers->get('Access-Control-Allow-Headers')
        );
        $this->assertEquals('Origin', $headers->get('Vary'));
    }

    public function testShouldNotAddCorsToNonApiRequests()
    {
        // Set
        $request = m::mock(Request::class.'[is]');
        $handler = $this->app->make(Handler::class);

        // Expect
        $request->shouldReceive('is')
            ->never();

        $this->expectException(Exception::class);

        // Act
        $handler->render($request, new Exception());
    }
}
