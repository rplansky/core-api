<?php
namespace Boitata\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery as m;
use TestCase;

/**
 * Test case for no-cache filter.
 */
class NoCacheTest extends TestCase
{
    public function testHandleShouldSendNoCacheHeader()
    {
        $middleware = new NoCache();
        $response = m::mock(Response::class);

        $response->shouldReceive('header')
            ->with('Cache-Control', 'no-cache,no-store,max-age=0')
            ->once()
            ->andReturn(true);

        $this->assertTrue(
            $middleware->handle(
                new Request(),
                function () use ($response) {
                    return $response;
                }
            )
        );
    }
}
